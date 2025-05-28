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
        Schema::table('money_safe', function (Blueprint $table) {
            $table->foreign(['user_id'], 'money_safe_ibfk_1')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['note_id'], 'money_safe_ibfk_3')->references(['id'])->on('notes_safe_money')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'money_safe_ibfk_2')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_safe', function (Blueprint $table) {
            $table->dropForeign('money_safe_ibfk_1');
            $table->dropForeign('money_safe_ibfk_3');
            $table->dropForeign('money_safe_ibfk_2');
        });
    }
};
