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
        // First, drop the existing table if it exists
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS user_subscriptions');

        // Create the table with proper references
        DB::connection('mysql')->statement('
            CREATE TABLE IF NOT EXISTS user_subscriptions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                plan_type ENUM("trial", "basic", "professional", "enterprise") NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                start_date DATETIME NOT NULL,
                end_date DATETIME NOT NULL,
                status ENUM("active", "expired", "cancelled") DEFAULT "active",
                auto_renew BOOLEAN DEFAULT FALSE,
                payment_method_id BIGINT UNSIGNED NULL,
                payment_id BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_payment_method (payment_method_id),
                INDEX idx_payment (payment_id),
                INDEX idx_status (status),
                INDEX idx_dates (start_date, end_date)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // Add some default trial subscriptions for testing
        DB::connection('mysql')->statement("
            INSERT INTO user_subscriptions (
                user_id, 
                plan_type, 
                price, 
                start_date, 
                end_date, 
                status, 
                auto_renew, 
                created_at, 
                updated_at
            ) VALUES 
            (1, 'trial', 0.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'active', false, NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS user_subscriptions');
    }
}; 