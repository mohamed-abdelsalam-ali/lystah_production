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
        Schema::table('stores_log', function (Blueprint $table) {
            $table->foreign(['store_id'], 'stores_log_ibfk_2')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_action_id'], 'stores_log_ibfk_3')->references(['id'])->on('store_action')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores_log', function (Blueprint $table) {
            $table->dropForeign('stores_log_ibfk_2');
            $table->dropForeign('stores_log_ibfk_3');
        });
    }
};
