<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_in_days');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
};
