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
        Schema::create('sale_transaction', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sale_pricing_id')->nullable()->index('sale_pricing_id');
            $table->integer('buy_company_id')->nullable()->index('buy_company_id');
            $table->dateTime('date')->nullable();
            $table->integer('seller')->nullable();
            $table->integer('client_id')->nullable()->index('client_id');
            $table->double('amount', 11, 0)->nullable();
            $table->integer('store_id')->nullable()->index('store_id');
            $table->integer('sale_type')->nullable()->index('sale_type');
            $table->integer('invoice_id')->nullable()->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_transaction');
    }
};
