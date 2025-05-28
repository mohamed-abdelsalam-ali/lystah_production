<?php

namespace App\Console\Commands;

use App\Models\Company\UserSubscription;
use App\Models\Company\SubscriptionPlan;
use Illuminate\Console\Command;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';
    protected $description = 'Check and update subscription statuses';

    public function handle()
    {
        // Check for expired subscriptions
        $expiredSubscriptions = UserSubscription::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);
            $this->info("Subscription #{$subscription->id} has been marked as expired.");
        }

        // Check for users trying to get free plan twice
        $freePlan = SubscriptionPlan::where('is_free', true)->first();
        
        if ($freePlan) {
            $usersWithFreePlan = UserSubscription::where('subscription_plan_id', $freePlan->id)
                ->where('user_id', '!=', null)
                ->pluck('user_id')
                ->unique();

            $this->info("Found {$usersWithFreePlan->count()} users who have used free plan before.");
        }

        $this->info('Subscription check completed.');
    }
} 