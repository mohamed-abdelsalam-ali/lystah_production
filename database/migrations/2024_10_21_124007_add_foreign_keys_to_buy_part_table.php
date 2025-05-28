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
        Schema::table('buy_part', function (Blueprint $table) {
            $table->foreign(['transaction_id'], 'buy_part_ibfk_1')->references(['id'])->on('buy_transaction')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['part_types_id'], 'buy_part_ibfk_6')->references(['id'])->on('part_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_part', function (Blueprint $table) {
            $table->dropForeign('buy_part_ibfk_1');
            $table->dropForeign('buy_part_ibfk_6');
        });
    }
};
