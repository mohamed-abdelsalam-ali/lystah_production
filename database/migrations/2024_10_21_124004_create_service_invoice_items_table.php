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
        Schema::create('service_invoice_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('serviceid')->nullable()->index('serviceid');
            $table->integer('price')->nullable();
            $table->integer('serviceinviceid')->nullable()->index('serviceinviceid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_invoice_items');
    }
};
