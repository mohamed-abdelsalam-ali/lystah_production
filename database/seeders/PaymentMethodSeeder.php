<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'name' => 'credit_card',
                'display_name' => 'Credit Card',
                'description' => 'Pay securely with your credit card',
                'icon' => 'fa-credit-card',
                'is_active' => true,
                'requires_credentials' => true,
                'credentials' => [
                    'requires_card_number' => true,
                    'requires_expiry' => true,
                    'requires_cvv' => true
                ]
            ],
            [
                'name' => 'paypal',
                'display_name' => 'PayPal',
                'description' => 'Pay with your PayPal account',
                'icon' => 'fa-paypal',
                'is_active' => true,
                'requires_credentials' => false,
                'credentials' => null
            ],
            [
                'name' => 'bank_transfer',
                'display_name' => 'Bank Transfer',
                'description' => 'Pay directly from your bank account',
                'icon' => 'fa-university',
                'is_active' => true,
                'requires_credentials' => true,
                'credentials' => [
                    'requires_account_name' => true,
                    'requires_account_number' => true,
                    'requires_bank_name' => true,
                    'requires_swift_code' => true
                ]
            ],
            [
                'name' => 'cash',
                'display_name' => 'Cash',
                'description' => 'Pay with cash on delivery',
                'icon' => 'fa-money-bill',
                'is_active' => true,
                'requires_credentials' => false,
                'credentials' => null
            ]
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}
