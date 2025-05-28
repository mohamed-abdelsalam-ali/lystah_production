<?php

namespace Database\Seeders;

use App\Models\Company\User;
use App\Models\Company\SubscriptionPlan;
use App\Models\Company\UserSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@lastinventory.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'telephone' => '+1234567890',
                'company_name' => 'Last Inventory Admin',
                'role' => 'admin',
                'is_active' => true,
                'settings' => [
                    'notifications' => true,
                    'two_factor' => false
                ]
            ]
        );

        // Create sample company users
        $companies = [
            [
                'username' => 'techcorp',
                'email' => 'manager@techcorp.com',
                'password' => Hash::make('password123'),
                'telephone' => '+1987654321',
                'company_name' => 'TechCorp Solutions',
                'db_name' => 'techcorp_db',
                'role' => 'user',
                'is_active' => true,
                'settings' => [
                    'notifications' => true,
                    'two_factor' => true
                ]
            ],
            [
                'username' => 'globalmart',
                'email' => 'info@globalmart.com',
                'password' => Hash::make('password123'),
                'telephone' => '+1122334455',
                'company_name' => 'Global Mart Retail',
                'db_name' => 'globalmart_db',
                'role' => 'user',
                'is_active' => true,
                'settings' => [
                    'notifications' => true,
                    'two_factor' => false
                ]
            ],
            [
                'username' => 'warehouse',
                'email' => 'support@warehouse.com',
                'password' => Hash::make('password123'),
                'telephone' => '+1555666777',
                'company_name' => 'Warehouse Pro',
                'db_name' => 'warehouse_db',
                'role' => 'user',
                'is_active' => true,
                'settings' => [
                    'notifications' => false,
                    'two_factor' => false
                ]
            ]
        ];

        // Get subscription plans
        $freePlan = SubscriptionPlan::where('name', 'free')->first();
        $starterPlan = SubscriptionPlan::where('name', 'starter')->first();
        $proPlan = SubscriptionPlan::where('name', 'professional')->first();

        // Create users and their subscriptions
        foreach ($companies as $index => $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign different plans to different users
            $plan = match($index) {
                0 => $proPlan,    // TechCorp gets Professional plan
                1 => $starterPlan,// GlobalMart gets Starter plan
                2 => $freePlan,   // Warehouse gets Free plan
                default => $freePlan
            };

            if ($plan) {
                UserSubscription::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'subscription_plan_id' => $plan->id
                    ],
                    [
                        'starts_at' => now(),
                        'ends_at' => now()->addDays($plan->duration_in_days),
                        'status' => 'active',
                        'subscription_details' => [
                            'plan_name' => $plan->name,
                            'price' => $plan->price
                        ]
                    ]
                );
            }
        }
    }
}
