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
        Schema::table('order_supplier', function (Blueprint $table) {
            $table->foreign(['transaction_id'], 'order_supplier_ibfk_1')->references(['id'])->on('buy_transaction')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['currency_id'], 'order_supplier_ibfk_3')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplier_id'], 'order_supplier_ibfk_2')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_supplier', function (Blueprint $table) {
            $table->dropForeign('order_supplier_ibfk_1');
            $table->dropForeign('order_supplier_ibfk_3');
            $table->dropForeign('order_supplier_ibfk_2');
        });
    }
};
