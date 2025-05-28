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
        Schema::table('damaged_parts', function (Blueprint $table) {
            $table->foreign(['supplier_order_id'], 'damaged_parts_ibfk_1')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['store_log_id'], 'damaged_parts_ibfk_2')->references(['id'])->on('stores_log')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('damaged_parts', function (Blueprint $table) {
            $table->dropForeign('damaged_parts_ibfk_1');
            $table->dropForeign('damaged_parts_ibfk_2');
        });
    }
};
