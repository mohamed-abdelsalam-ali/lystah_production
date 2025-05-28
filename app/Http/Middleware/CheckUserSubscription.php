<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company\UserSubscription;
use App\Models\Company\User;
use Carbon\Carbon;

class CheckUserSubscription
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user_general = User::on('mysql_general')->where('email', $user->email)->first();

            if ($user_general) {
                // Check for expired subscriptions
                $expiredSubscriptions = UserSubscription::on('mysql_general')
                    ->where('user_id', $user_general->id)
                    ->where('status', 'active')
                    ->where('ends_at', '<=', Carbon::now())
                    ->get();

                foreach ($expiredSubscriptions as $subscription) {
                    $subscription->update(['status' => 'expired']);
                }

                // Check if user has any active subscription
                $hasActiveSubscription = UserSubscription::on('mysql_general')
                    ->where('user_id', $user_general->id)
                    ->where('status', 'active')
                    ->where('ends_at', '>', Carbon::now())
                    ->exists();

                if (!$hasActiveSubscription) {
                    // Store subscription status in session for use in views
                    session(['subscription_expired' => true]);
                } else {
                    session(['subscription_expired' => false]);
                }
            }
        }

        return $next($request);
    }
} 