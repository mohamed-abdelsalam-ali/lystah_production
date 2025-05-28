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
        Schema::table('store2', function (Blueprint $table) {
            $table->foreign(['supplier_order_id'], 'store2_ibfk_112')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['store_log_id'], 'store2_ibfk_251')->references(['id'])->on('stores_log')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store2', function (Blueprint $table) {
            $table->dropForeign('store2_ibfk_112');
            $table->dropForeign('store2_ibfk_251');
        });
    }
};
