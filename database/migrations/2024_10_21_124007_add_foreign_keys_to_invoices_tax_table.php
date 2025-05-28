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
        Schema::table('invoices_tax', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'invoices_tax_ibfk_1')->references(['id'])->on('invoice')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tax_id'], 'invoices_tax_ibfk_2')->references(['id'])->on('taxes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices_tax', function (Blueprint $table) {
            $table->dropForeign('invoices_tax_ibfk_1');
            $table->dropForeign('invoices_tax_ibfk_2');
        });
    }
};
