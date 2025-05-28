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
        Schema::table('invoice_items_ordersupplier', function (Blueprint $table) {
            $table->foreign(['invoice_item_id'], 'invoice_items_ordersupplier_ibfk_1')->references(['id'])->on('invoice_items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['order_supplier_id'], 'invoice_items_ordersupplier_ibfk_2')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_items_ordersupplier', function (Blueprint $table) {
            $table->dropForeign('invoice_items_ordersupplier_ibfk_1');
            $table->dropForeign('invoice_items_ordersupplier_ibfk_2');
        });
    }
};
