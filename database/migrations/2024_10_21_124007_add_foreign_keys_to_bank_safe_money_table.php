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
        Schema::table('bank_safe_money', function (Blueprint $table) {
            $table->foreign(['user_id'], 'bank_safe_money_ibfk_1')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['bank_type_id'], 'bank_safe_money_ibfk_4')->references(['id'])->on('bank_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['currency_id'], 'bank_safe_money_ibfk_3')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_safe_money', function (Blueprint $table) {
            $table->dropForeign('bank_safe_money_ibfk_1');
            $table->dropForeign('bank_safe_money_ibfk_4');
            $table->dropForeign('bank_safe_money_ibfk_3');
        });
    }
};
