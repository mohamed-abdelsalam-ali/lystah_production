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
        Schema::table('client_discount', function (Blueprint $table) {
            $table->foreign(['client_id'], 'client_discount_ibfk_1')->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_discount', function (Blueprint $table) {
            $table->dropForeign('client_discount_ibfk_1');
        });
    }
};
