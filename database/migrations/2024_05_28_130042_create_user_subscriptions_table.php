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
            CREATE TABLE IF NOT EXISTS user_subscriptions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                plan_type VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                start_date DATETIME NOT NULL,
                end_date DATETIME NOT NULL,
                status ENUM("active", "expired", "cancelled") DEFAULT "active",
                auto_renew BOOLEAN DEFAULT FALSE,
                payment_method VARCHAR(255) NULL,
                transaction_id VARCHAR(255) NULL,
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
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS user_subscriptions');
    }
}; 