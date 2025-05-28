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
        Schema::table('buy_bill_img', function (Blueprint $table) {
            $table->foreign(['buy_trans_id'], 'buy_bill_img_ibfk_1')->references(['id'])->on('buy_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_bill_img', function (Blueprint $table) {
            $table->dropForeign('buy_bill_img_ibfk_1');
        });
    }
};
