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
        Schema::create('invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('casher_id')->nullable();
            $table->decimal('discount', 11, 3)->nullable();
            $table->decimal('actual_price', 10)->nullable();
            $table->integer('client_id')->nullable()->index('client_id');
            $table->integer('company_id')->nullable()->index('company_id');
            $table->integer('store_id')->nullable()->index('store_id');
            $table->decimal('price_without_tax', 10, 3)->nullable();
            $table->decimal('tax_amount', 10, 3)->nullable();
            $table->decimal('paied', 11, 3)->nullable();
            $table->integer('flag')->nullable()->default(0)->comment('0 -- كاش
1-- مديونية');
            $table->integer('presale_order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
};
