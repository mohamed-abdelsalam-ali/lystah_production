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
        Schema::table('buy_transaction', function (Blueprint $table) {
            $table->foreign(['company_id'], 'buy_transaction_ibfk_1')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_transaction', function (Blueprint $table) {
            $table->dropForeign('buy_transaction_ibfk_1');
        });
    }
};
