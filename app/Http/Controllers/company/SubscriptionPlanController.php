<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::on('mysql_general')->get();
        return view('subscription-plans.index', compact('subscriptionPlans'));
    }

    public function create()
    {
        return view('subscription-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            
            // Process features array
            if ($request->has('features') && is_array($request->features)) {
                $features = array_filter($request->features, function($feature) {
                    return !empty($feature['name']);
                });
                $validated['features'] = array_values($features);
            } else {
                $validated['features'] = [];
            }

            // Ensure numeric values are properly formatted
            $validated['price'] = (float) $validated['price'];
            $validated['duration_in_days'] = (int) $validated['duration_in_days'];
            $validated['is_active'] = (bool) $validated['is_active'];
            
            $subscriptionPlan = SubscriptionPlan::on('mysql_general')->create($validated);
            
            DB::commit();
            
            return redirect()->route('subscription-plans.index')
                ->with('success', 'Subscription plan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create subscription plan: ' . $e->getMessage());
        }
    }

    public function show($subscriptionPlan)
    {
        $subscriptionPlan = SubscriptionPlan::on('mysql_general')->findOrFail($subscriptionPlan);
        return view('subscription-plans.show', compact('subscriptionPlan'));
    }

    public function edit($subscriptionPlan)
    {
        $subscriptionPlan = SubscriptionPlan::on('mysql_general')->findOrFail($subscriptionPlan);
        return view('subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, $subscriptionPlan)
    {
        $subscriptionPlan = SubscriptionPlan::on('mysql_general')->findOrFail($subscriptionPlan);
        
        $validated = $request->validate([
           'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean'
        ]);
        try {
            DB::beginTransaction();
            
            // Process features array
            if ($request->has('features') && is_array($request->features)) {
                $features = array_filter($request->features, function($feature) {
                    return !empty($feature['name']);
                });
                $validated['features'] = array_values($features);
            } else {
                $validated['features'] = [];
            }

            // Ensure numeric values are properly formatted
            $validated['price'] = (float) $validated['price'];
            $validated['duration_in_days'] = (int) $validated['duration_in_days'];
            $validated['is_active'] = (bool) $validated['is_active'];
            $subscriptionPlan->setConnection('mysql_general')->update($validated);
            
            DB::commit();
            
            return redirect()->route('subscription-plans.index')
                ->with('success', 'Subscription plan updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update subscription plan: ' . $e->getMessage());
        }
    }

    public function destroy($subscriptionPlan)
    {
        try {
            DB::beginTransaction();
            
            $subscriptionPlan = SubscriptionPlan::on('mysql_general')->findOrFail($subscriptionPlan);
            $subscriptionPlan->delete();
            
            DB::commit();
            
            return redirect()->route('subscription-plans.index')
                ->with('success', 'Subscription plan deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete subscription plan: ' . $e->getMessage());
        }
    }
}
