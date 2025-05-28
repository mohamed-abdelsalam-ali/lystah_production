<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_subscriptions')) {
            Schema::create('user_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('subscription_plan_id');
                $table->dateTime('starts_at');
                $table->dateTime('ends_at');
                $table->string('status');  // active, expired, cancelled, suspended
                $table->text('cancellation_reason')->nullable();
                $table->dateTime('cancelled_at')->nullable();
                $table->json('subscription_details')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index('ends_at');

                // Add foreign keys if the referenced tables exist
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (Schema::hasTable('subscription_plans')) {
                    $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
