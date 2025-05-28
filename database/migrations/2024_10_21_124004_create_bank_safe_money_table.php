<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_safe_money', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('notes', 255)->nullable();
            $table->date('date')->nullable();
            $table->string('flag', 255)->nullable();
            $table->decimal('money', 10)->nullable();
            $table->decimal('total', 10)->nullable();
            $table->string('type_money', 255)->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('store_id')->nullable()->index('store_id');
            $table->decimal('money_currency', 10)->nullable();
            $table->integer('currency_id')->nullable()->index('currency_id');
            $table->integer('bank_type_id')->nullable()->index('bank_type_id');
            $table->string('img_path', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_safe_money');
    }
};
