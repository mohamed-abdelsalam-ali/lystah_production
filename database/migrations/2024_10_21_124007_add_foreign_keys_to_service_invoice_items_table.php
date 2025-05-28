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
        Schema::table('service_invoice_items', function (Blueprint $table) {
            $table->foreign(['serviceid'], 'service_invoice_items_ibfk_1')->references(['id'])->on('service')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['serviceinviceid'], 'service_invoice_items_ibfk_2')->references(['id'])->on('service_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_invoice_items', function (Blueprint $table) {
            $table->dropForeign('service_invoice_items_ibfk_1');
            $table->dropForeign('service_invoice_items_ibfk_2');
        });
    }
};
