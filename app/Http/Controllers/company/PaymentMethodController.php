<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::on('mysql_general')->get();
        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('payment-methods.create');
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mysql_general.payment_methods',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'requires_credentials' => 'boolean',
            'credentials' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();
            
            // Process credentials array
            if ($request->has('credentials') && is_array($request->credentials)) {
                $credentials = [];
                foreach ($request->credentials as $item) {
                    if (!empty($item['key']) && isset($item['value'])) {
                        $credentials[$item['key']] = $item['value'];
                    }
                }
                $validated['credentials'] = $credentials;
            } else {
                $validated['credentials'] = null;
            }
            
            $paymentMethod = PaymentMethod::on('mysql_general')->create($validated);
            
            DB::commit();
            
            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create payment method: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment method.
     */
    public function show($paymentMethod)
    {
        $paymentMethod = PaymentMethod::on('mysql_general')->findOrFail($paymentMethod);
        return view('payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, $paymentMethod)
    {
        $paymentMethod = PaymentMethod::on('mysql_general')->findOrFail($paymentMethod);
        // return $paymentMethod;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'requires_credentials' => 'boolean',
            'credentials' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();
            
            // Process credentials array
            if ($request->has('credentials') && is_array($request->credentials)) {
                $credentials = [];
                foreach ($request->credentials as $item) {
                    if (!empty($item['key']) && isset($item['value'])) {
                        $credentials[$item['key']] = $item['value'];
                    }
                }
                $validated['credentials'] = $credentials;
            } else {
                $validated['credentials'] = null;
            }
            
            $paymentMethod->setConnection('mysql_general')->update($validated);
            
            DB::commit();
            
            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment method: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment method.
     */
    public function destroy($paymentMethod)
    {
        try {
            DB::beginTransaction();
            
            $paymentMethod = PaymentMethod::on('mysql_general')->findOrFail($paymentMethod);
            $paymentMethod->delete();
            
            DB::commit();
            
            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete payment method: ' . $e->getMessage());
        }
    }
} 