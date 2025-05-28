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
        Schema::create('invoices_tax', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->integer('tax_id')->nullable()->index('invoices_tax_ibfk_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices_tax');
    }
};
