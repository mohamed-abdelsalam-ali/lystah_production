<?php

namespace App\Http\Controllers\Company;

use App\Models\Payment;
use App\Models\Company\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Company\User;
use App\Models\Company\UserPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubscriptionPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
        
        
        // Check if user is a parent company admin in the general database
        $parentCompanyAdmin = DB::connection('mysql_general')
            ->table('users')
            ->where('db_name', $generalUser->db_name)
            ->where('role_name', 'Admin')
           
            ->first();

        // if ($parentCompanyAdmin && $generalUser->id === $parentCompanyAdmin->id) {
        //     return redirect()->route('company.dashboard');
        // }
        if ( $generalUser->id !== $parentCompanyAdmin->id) {
          session()->flash('error', ' Company payment status: pending  .');
          return redirect()->route('login');
        }
        
        $payments = $generalUser->payments()
            ->orderBy('created_at', 'desc')
            ->get();

        $latestPayment = $payments->first();

        return view('subscription.payments', [
            'payments' => $payments,
            'user' => $generalUser,
            'latestPayment' => $latestPayment
        ]);
    }

    public function show($paymentId)
    {
        $user = Auth::user();
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
        
        $payment = $generalUser->payments()
            ->findOrFail($paymentId);

        return view('subscription.payment-details', compact('payment'));
    }

    public function showRetry($paymentId)
    {
        $user = Auth::user();
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
        
        $payment = $generalUser->payments()
            ->findOrFail($paymentId);

        // Check if payment can be retried
        if ($payment->status !== 'pending' && $payment->status !== 'failed') {
            return redirect()
                ->route('subscription.payment.show', $payment->id)
                ->with('error', 'This payment cannot be retried.');
        }

        // Get available payment methods from general database
        $paymentMethods = PaymentMethod::on('mysql_general')
            ->where('is_active', true)
            ->get();

        return view('subscription.payment-retry', compact('payment', 'paymentMethods'));
    }

    public function retry(Request $request, $paymentId)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:mysql_general.payment_methods,id',
            'card_number' => 'required_if:payment_method_id,1|string|size:16',
            'expiry_month' => 'required_if:payment_method_id,1|integer|between:1,12',
            'expiry_year' => 'required_if:payment_method_id,1|integer|min:' . date('Y'),
            'cvv' => 'required_if:payment_method_id,1|string|size:3',
        ]);

        $user = Auth::user();
        $generalUser = User::on('mysql_general')->where('email', $user->email)->first();
        
        $payment = $generalUser->payments()
            ->findOrFail($paymentId);

        // Check if payment can be retried
        if ($payment->status !== 'pending' && $payment->status !== 'failed') {
            return redirect()
                ->route('subscription.payment.show', $payment->id)
                ->with('error', 'This payment cannot be retried.');
        }

        // Get payment method from general database
        $paymentMethod = PaymentMethod::on('mysql_general')
            ->findOrFail($request->payment_method_id);

        try {
            // Process payment based on selected method
            switch ($paymentMethod->code) {
                case 'card':
                    // Process credit card payment
                    $paymentResult = $this->processCardPayment($request, $payment);
                    break;
                case 'paypal':
                    // Process PayPal payment
                    $paymentResult = $this->processPayPalPayment($request, $payment);
                    break;
                case 'bank_transfer':
                case 'cash':
                    // Process manual confirmation methods
                    $paymentResult = $this->processManualPayment($paymentMethod, $payment);
                    break;
                default:
                    throw new \Exception('Invalid payment method');
            }

            if ($paymentResult['success']) {
                // Update payment status
                $payment->update([
                    'status' => $paymentResult['status'] ?? 'completed',
                    'transaction_id' => $paymentResult['transaction_id'],
                    'payment_method_id' => $paymentMethod->id,
                    'payment_details' => $paymentResult['details'] ?? null,
                ]);

                // If payment is completed, update user's subscription status
                if ($paymentResult['status'] === 'completed') {
                    $generalUser->update([
                        'payment_status' => 'completed',
                        'subscription_status' => 'active'
                    ]);
                }

                return redirect()
                    ->route('subscription.payment.show', $payment->id)
                    ->with('success', $paymentResult['message']);
            } else {
                throw new \Exception($paymentResult['message']);
            }
        } catch (\Exception $e) {
            Log::error('Payment retry failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processCardPayment($request, $payment)
    {
        try {
            // Here you would integrate with your credit card payment gateway
            // Example using Stripe:
            /*
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $payment->amount * 100,
                'currency' => $payment->currency,
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
            ]);
            */

            // For now, simulate a successful payment
            return [
                'success' => true,
                'status' => 'completed',
                'transaction_id' => 'CARD' . time(),
                'message' => 'Credit card payment processed successfully',
                'details' => [
                    'card_last4' => substr($request->card_number, -4),
                    'card_type' => $this->getCardType($request->card_number)
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Card payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process card payment: ' . $e->getMessage()
            ];
        }
    }

    private function processPayPalPayment($request, $payment)
    {
        try {
            // Here you would integrate with PayPal API
            // Example using PayPal SDK:
            /*
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config('services.paypal.client_id'),
                    config('services.paypal.secret')
                )
            );

            $payment = new \PayPal\Api\Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            $payment->create($apiContext);
            */

            // For now, simulate a successful payment
            return [
                'success' => true,
                'status' => 'completed',
                'transaction_id' => 'PP' . time(),
                'message' => 'PayPal payment processed successfully',
                'details' => [
                    'payer_email' => Auth::user()->email
                ]
            ];
        } catch (\Exception $e) {
            Log::error('PayPal payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process PayPal payment: ' . $e->getMessage()
            ];
        }
    }

    private function processManualPayment($paymentMethod, $payment)
    {
        try {
            // For manual payment methods (bank transfer and cash)
            // Set status to pending and provide instructions
            
            $details = [];
            $message = '';

            if ($paymentMethod->code === 'bank_transfer') {
                $details = [
                    'bank_name' => config('payment.bank_name'),
                    'account_number' => config('payment.account_number'),
                    'account_name' => config('payment.account_name'),
                    'reference' => 'PAY-' . $payment->id
                ];
                $message = 'Bank transfer details have been sent. Please complete the transfer to activate your subscription.';
            } else {
                $details = [
                    'location' => config('payment.cash_location'),
                    'reference' => 'PAY-' . $payment->id
                ];
                $message = 'Please visit our office to complete the payment.';
            }

            return [
                'success' => true,
                'status' => 'pending',
                'transaction_id' => strtoupper($paymentMethod->code) . time(),
                'message' => $message,
                'details' => $details
            ];
        } catch (\Exception $e) {
            Log::error('Manual payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process manual payment: ' . $e->getMessage()
            ];
        }
    }

    private function getCardType($cardNumber)
    {
        // Simple card type detection
        $firstDigit = substr($cardNumber, 0, 1);
        switch ($firstDigit) {
            case '4':
                return 'Visa';
            case '5':
                return 'Mastercard';
            case '3':
                return 'American Express';
            default:
                return 'Unknown';
        }
    }
} 