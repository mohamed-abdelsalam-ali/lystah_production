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
        Schema::table('service_taxes', function (Blueprint $table) {
            $table->foreign(['service_invoice_id'], 'service_taxes_ibfk_1')->references(['id'])->on('service_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['tax_id'], 'service_taxes_ibfk_2')->references(['id'])->on('taxes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_taxes', function (Blueprint $table) {
            $table->dropForeign('service_taxes_ibfk_1');
            $table->dropForeign('service_taxes_ibfk_2');
        });
    }
};
