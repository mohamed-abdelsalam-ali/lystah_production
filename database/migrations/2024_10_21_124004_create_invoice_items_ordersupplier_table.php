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
        Schema::create('invoice_items_ordersupplier', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_item_id')->nullable()->index('invoice_item_id');
            $table->integer('order_supplier_id')->nullable()->index('order_supplier_id');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->integer('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items_ordersupplier');
    }
};
