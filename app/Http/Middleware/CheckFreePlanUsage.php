<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company\UserSubscription;
use App\Models\Company\SubscriptionPlan;

class CheckFreePlanUsage
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $planId = $request->input('subscription_plan_id');

        if (!$user || !$planId) {
            return $next($request);
        }

        $plan = SubscriptionPlan::find($planId);

        if ($plan && $plan->is_free) {
            $hasUsedFreePlan = UserSubscription::where('user_id', $user->id)
                ->whereHas('subscriptionPlan', function ($query) {
                    $query->where('is_free', true);
                })
                ->exists();

            if ($hasUsedFreePlan) {
                return response()->json([
                    'message' => 'You have already used the free plan before. Please choose a paid plan.',
                    'status' => 'error'
                ], 403);
            }
        }

        return $next($request);
    }
} 