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
        Schema::create('service_taxes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('service_invoice_id')->nullable()->index('service_invoice_id');
            $table->integer('tax_id')->nullable()->index('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_taxes');
    }
};
