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
        Schema::table('presale_order', function (Blueprint $table) {
            $table->foreign(['client_id'], 'presale_order_ibfk_1')->references(['id'])->on('clients')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['store_id'], 'presale_order_ibfk_2')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presale_order', function (Blueprint $table) {
            $table->dropForeign('presale_order_ibfk_1');
            $table->dropForeign('presale_order_ibfk_2');
        });
    }
};
