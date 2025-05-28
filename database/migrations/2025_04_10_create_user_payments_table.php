<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_payments')) {
            Schema::create('user_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('subscription_id');
                $table->unsignedBigInteger('payment_method_id');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3);
                $table->string('status');  // pending, completed, failed, refunded
                $table->string('transaction_id')->nullable();
                $table->json('payment_details')->nullable();
                $table->text('error_message')->nullable();
                $table->dateTime('paid_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index('transaction_id');
                $table->index('paid_at');

                // Add foreign keys if the referenced tables exist
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (Schema::hasTable('user_subscriptions')) {
                    $table->foreign('subscription_id')->references('id')->on('user_subscriptions')->onDelete('restrict');
                }
                if (Schema::hasTable('payment_methods')) {
                    $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_payments');
    }
};
