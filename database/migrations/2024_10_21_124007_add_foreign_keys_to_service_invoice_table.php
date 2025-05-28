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
        Schema::table('service_invoice', function (Blueprint $table) {
            $table->foreign(['serviceoptionid'], 'service_invoice_ibfk_1')->references(['id'])->on('serviceoption')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['servicetypeid'], 'service_invoice_ibfk_2')->references(['id'])->on('servicetype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_invoice', function (Blueprint $table) {
            $table->dropForeign('service_invoice_ibfk_1');
            $table->dropForeign('service_invoice_ibfk_2');
        });
    }
};
