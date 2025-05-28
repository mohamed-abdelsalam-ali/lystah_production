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
            CREATE TABLE IF NOT EXISTS subscription_plans (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                price DECIMAL(10, 2) NOT NULL,
                duration_days INT NOT NULL,
                max_users INT NOT NULL,
                features JSON NOT NULL,
                is_trial BOOLEAN DEFAULT FALSE,
                is_popular BOOLEAN DEFAULT FALSE,
                status ENUM("active", "inactive") DEFAULT "active",
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        // Insert default plans
        DB::connection('mysql')->statement("
            INSERT INTO subscription_plans (name, description, price, duration_days, max_users, features, is_trial, is_popular, status) VALUES
            ('التجربة المجانية', 'تجربة مجانية لمدة 30 يوم', 0.00, 30, 10, '[\"وصول كامل لجميع الميزات\", \"إدارة المخزون الكاملة\", \"دعم فني كامل\", \"تقارير متقدمة\"]', true, false, 'active'),
            ('الأساسي', 'الخطة الأساسية للمشاريع الصغيرة', 29.00, 30, 5, '[\"إدارة المخزون الأساسية\", \"دعم عبر البريد الإلكتروني\", \"التقارير الأساسية\"]', false, false, 'active'),
            ('المحترف', 'الخطة المثالية للشركات المتوسطة', 79.00, 30, 20, '[\"إدارة المخزون المتقدمة\", \"دعم ذو أولوية\", \"تقارير متقدمة\", \"وصول API\"]', false, true, 'active'),
            ('المؤسسة', 'الخطة الشاملة للشركات الكبيرة', 199.00, 30, 999999, '[\"إدارة المخزون الكاملة\", \"دعم 24/7\", \"تقارير مخصصة\", \"وصول API كامل\", \"تكامل مخصص\"]', false, false, 'active')
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('mysql')->statement('DROP TABLE IF EXISTS subscription_plans');
    }
}; 