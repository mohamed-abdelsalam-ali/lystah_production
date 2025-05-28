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
        Schema::table('invoice', function (Blueprint $table) {
            $table->foreign(['client_id'], 'invoice_ibfk_1')->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'invoice_ibfk_3')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['company_id'], 'invoice_ibfk_2')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign('invoice_ibfk_1');
            $table->dropForeign('invoice_ibfk_3');
            $table->dropForeign('invoice_ibfk_2');
        });
    }
};
