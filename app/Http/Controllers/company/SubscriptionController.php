<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\PaymentMethod;
use App\Models\Company\SubscriptionPlan;
use App\Models\Company\UserSubscription;
use App\Models\Company\UserPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Company\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function showRenewalPage()
    {
        $user = auth()->user();
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        $currentSubscription = UserSubscription::on('mysql_general')->where('user_id', $user_general->id)
            ->with(['subscriptionPlan'])
            ->latest()
            ->first();

        $plans = SubscriptionPlan::on('mysql_general')->where('is_active', true)->get();
        $paymentMethods = PaymentMethod::on('mysql_general')->where('is_active', true)->get();

        return view('subscription.renew', compact('currentSubscription', 'plans', 'paymentMethods'));
    }

    public function processRenewal(Request $request)
    {
        $request->validate([
            'plan_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!DB::connection('mysql_general')->table('subscription_plans')->where('id', $value)->exists()) {
                        $fail('The selected plan does not exist.');
                    }
                }
            ],
            'payment_method_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!DB::connection('mysql_general')->table('payment_methods')->where('id', $value)->exists()) {
                        $fail('The selected payment method does not exist.');
                    }
                }
            ],
        ]);

        $user = auth()->user();
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        $plan = SubscriptionPlan::on('mysql_general')->findOrFail($request->plan_id);

        // Check if user has already used a free plan
        if ($plan->is_free) {
            $hasUsedFreePlan = UserSubscription::on('mysql_general')
                ->where('user_id', $user_general->id)
                ->whereHas('subscriptionPlan', function ($query) {
                    $query->where('is_free', true);
                })
                ->exists();

            if ($hasUsedFreePlan) {
                return redirect()->back()
                    ->with('error', 'You have already used the free plan before. Please choose a paid plan.');
            }
        }

        // Create new subscription
        $subscription = new UserSubscription([
            'user_id' => $user_general->id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addDays($plan->duration_in_days),
            'status' => 'active',
            'price' => $plan->price
        ]);
        $subscription->setConnection('mysql_general')->save();

        // Create payment record
        if (!$plan->is_free) {
            $paymentMethod = PaymentMethod::on('mysql_general')->find($request->payment_method_id);
            $payment = new UserPayment([
                'user_id' => $user_general->id,
                'subscription_id' => $subscription->id,
                'payment_method_id' => $request->payment_method_id,
                'amount' => $plan->price,
                'status' => $paymentMethod->name === 'Cash' ? 'paid' : 'pending',
                'paid_at' => $paymentMethod->name === 'Cash' ? Carbon::now() : null
            ]);
            $payment->setConnection('mysql_general')->save();

            // If payment method is cash, activate subscription immediately
            if ($paymentMethod->name === 'Cash') {
                $subscription->status = 'active';
                $subscription->setConnection('mysql_general')->save();
            }
        }

        // Deactivate old subscription if exists
        UserSubscription::on('mysql_general')->where('user_id', $user_general->id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        return redirect()->route('company.dashboard')
            ->with('success', 'تم تجديد الاشتراك بنجاح!');
    }
}
