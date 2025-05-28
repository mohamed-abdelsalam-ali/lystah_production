<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'free',
                'display_name' => 'Free Plan',
                'description' => 'Basic features for small businesses',
                'price' => 0,
                'duration_in_days' => 30,
                'features' => [
                    ['name' => 'Up to 100 products'],
                    ['name' => 'Basic inventory management'],
                    ['name' => 'Single user'],
                    ['name' => 'Email support'],
                ],
                'is_active' => true,
                'is_free' => true,
                'is_popular' => false
            ],
            [
                'name' => 'starter',
                'display_name' => 'Starter Plan',
                'description' => 'Essential features for growing businesses',
                'price' => 29.99,
                'duration_in_days' => 30,
                'features' => [
                    ['name' => 'Up to 1,000 products'],
                    ['name' => 'Advanced inventory management'],
                    ['name' => 'Up to 3 users'],
                    ['name' => 'Priority email support'],
                    ['name' => 'Basic analytics'],
                ],
                'is_active' => true,
                'is_free' => false,
                'is_popular' => false
            ],
            [
                'name' => 'professional',
                'display_name' => 'Professional Plan',
                'description' => 'Advanced features for established businesses',
                'price' => 99.99,
                'duration_in_days' => 30,
                'features' => [
                    ['name' => 'Unlimited products'],
                    ['name' => 'Advanced inventory management'],
                    ['name' => 'Unlimited users'],
                    ['name' => '24/7 Priority support'],
                    ['name' => 'Advanced analytics'],
                    ['name' => 'API access'],
                    ['name' => 'Custom reports'],
                ],
                'is_active' => true,
                'is_free' => false,
                'is_popular' => true
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
