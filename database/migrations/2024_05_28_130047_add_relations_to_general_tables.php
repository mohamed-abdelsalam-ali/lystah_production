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
        // First, ensure all tables exist and have the correct structure
        $this->ensureTablesExist();

        // Add foreign key constraints to user_subscriptions table
        DB::connection('mysql')->statement('
            ALTER TABLE user_subscriptions
            MODIFY COLUMN user_id BIGINT UNSIGNED NULL,
            MODIFY COLUMN payment_method_id BIGINT UNSIGNED NULL,
            MODIFY COLUMN payment_id BIGINT UNSIGNED NULL
        ');

        // Add foreign key constraints to user_payments table
        DB::connection('mysql')->statement('
            ALTER TABLE user_payments
            MODIFY COLUMN user_id BIGINT UNSIGNED NULL,
            MODIFY COLUMN subscription_id BIGINT UNSIGNED NULL,
            MODIFY COLUMN payment_method_id BIGINT UNSIGNED NULL
        ');

        // Add indexes for better performance
        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_subscriptions
                ADD INDEX idx_user_subscriptions_user_id (user_id),
                ADD INDEX idx_user_subscriptions_payment_method_id (payment_method_id),
                ADD INDEX idx_user_subscriptions_payment_id (payment_id),
                ADD INDEX idx_user_subscriptions_status (status),
                ADD INDEX idx_user_subscriptions_dates (start_date, end_date)
            ');
        } catch (\Exception $e) {
            // Indexes might already exist, continue
        }

        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_payments
                ADD INDEX idx_user_payments_user_id (user_id),
                ADD INDEX idx_user_payments_subscription_id (subscription_id),
                ADD INDEX idx_user_payments_payment_method_id (payment_method_id),
                ADD INDEX idx_user_payments_status (status),
                ADD INDEX idx_user_payments_transaction_id (transaction_id)
            ');
        } catch (\Exception $e) {
            // Indexes might already exist, continue
        }

        // Add foreign key constraints with ON DELETE SET NULL for existing data
        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_subscriptions
                ADD CONSTRAINT fk_user_subscriptions_user_id
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE SET NULL,
                ADD CONSTRAINT fk_user_subscriptions_payment_method_id
                FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id)
                ON DELETE SET NULL,
                ADD CONSTRAINT fk_user_subscriptions_payment_id
                FOREIGN KEY (payment_id) REFERENCES user_payments(id)
                ON DELETE SET NULL
            ');
        } catch (\Exception $e) {
            // Constraints might already exist, continue
        }

        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_payments
                ADD CONSTRAINT fk_user_payments_user_id
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE SET NULL,
                ADD CONSTRAINT fk_user_payments_subscription_id
                FOREIGN KEY (subscription_id) REFERENCES user_subscriptions(id)
                ON DELETE SET NULL,
                ADD CONSTRAINT fk_user_payments_payment_method_id
                FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id)
                ON DELETE SET NULL
            ');
        } catch (\Exception $e) {
            // Constraints might already exist, continue
        }
    }

    /**
     * Ensure all required tables exist with correct structure
     */
    private function ensureTablesExist(): void
    {
        // Create users table if it doesn't exist
        DB::connection('mysql')->statement('
            CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // Create payment_methods table if it doesn't exist
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

        // Create user_subscriptions table if it doesn't exist
        DB::connection('mysql')->statement('
            CREATE TABLE IF NOT EXISTS user_subscriptions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                plan_type ENUM("trial", "basic", "professional", "enterprise") NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                start_date DATETIME NOT NULL,
                end_date DATETIME NOT NULL,
                status ENUM("active", "expired", "cancelled") DEFAULT "active",
                auto_renew BOOLEAN DEFAULT FALSE,
                payment_method_id BIGINT UNSIGNED NULL,
                payment_id BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // Create user_payments table if it doesn't exist
        DB::connection('mysql')->statement('
            CREATE TABLE IF NOT EXISTS user_payments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                subscription_id BIGINT UNSIGNED NULL,
                payment_method_id BIGINT UNSIGNED NULL,
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
        // Remove foreign key constraints from user_subscriptions table
        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_subscriptions
                DROP FOREIGN KEY fk_user_subscriptions_user_id,
                DROP FOREIGN KEY fk_user_subscriptions_payment_method_id,
                DROP FOREIGN KEY fk_user_subscriptions_payment_id
            ');
        } catch (\Exception $e) {
            // Constraints might not exist, continue
        }

        // Remove foreign key constraints from user_payments table
        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_payments
                DROP FOREIGN KEY fk_user_payments_user_id,
                DROP FOREIGN KEY fk_user_payments_subscription_id,
                DROP FOREIGN KEY fk_user_payments_payment_method_id
            ');
        } catch (\Exception $e) {
            // Constraints might not exist, continue
        }

        // Remove indexes
        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_subscriptions
                DROP INDEX idx_user_subscriptions_user_id,
                DROP INDEX idx_user_subscriptions_payment_method_id,
                DROP INDEX idx_user_subscriptions_payment_id,
                DROP INDEX idx_user_subscriptions_status,
                DROP INDEX idx_user_subscriptions_dates
            ');
        } catch (\Exception $e) {
            // Indexes might not exist, continue
        }

        try {
            DB::connection('mysql')->statement('
                ALTER TABLE user_payments
                DROP INDEX idx_user_payments_user_id,
                DROP INDEX idx_user_payments_subscription_id,
                DROP INDEX idx_user_payments_payment_method_id,
                DROP INDEX idx_user_payments_status,
                DROP INDEX idx_user_payments_transaction_id
            ');
        } catch (\Exception $e) {
            // Indexes might not exist, continue
        }
    }
}; 