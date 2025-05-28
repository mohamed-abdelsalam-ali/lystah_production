<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection('mysql')->statement('
            CREATE TABLE IF NOT EXISTS payment_methods (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                display_name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                icon VARCHAR(255) NULL,
                is_active BOOLEAN DEFAULT TRUE,
                requires_credentials BOOLEAN DEFAULT FALSE,
                credentials JSON NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // Insert default payment methods
        DB::connection('mysql')->statement("
            INSERT INTO payment_methods (name, display_name, description, icon, is_active, requires_credentials) VALUES
            ('credit_card', 'بطاقة ائتمان', 'الدفع باستخدام بطاقة الائتمان', 'fa-credit-card', true, true),
            ('bank_transfer', 'تحويل بنكي', 'الدفع عن طريق التحويل البنكي', 'fa-university', true, false),
            ('paypal', 'باي بال', 'الدفع باستخدام باي بال', 'fa-paypal', true, true),
            ('apple_pay', 'آبل باي', 'الدفع باستخدام آبل باي', 'fa-apple', true, true),
            ('google_pay', 'جوجل باي', 'الدفع باستخدام جوجل باي', 'fa-google', true, true)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS payment_methods');
    }
}; 