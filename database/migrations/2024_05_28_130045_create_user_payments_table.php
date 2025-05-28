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
            CREATE TABLE IF NOT EXISTS user_payments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                subscription_id BIGINT UNSIGNED NOT NULL,
                payment_method_id BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                currency VARCHAR(3) DEFAULT "USD",
                status ENUM("pending", "completed", "failed", "refunded") DEFAULT "pending",
                transaction_id VARCHAR(255) NULL,
                payment_details JSON NULL,
                error_message TEXT NULL,
                paid_at TIMESTAMP NULL DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS user_payments');
    }
}; 