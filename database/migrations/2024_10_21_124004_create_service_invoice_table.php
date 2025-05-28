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
        Schema::create('service_invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('date')->nullable();
            $table->integer('serviceoptionid')->nullable()->index('serviceoptionid');
            $table->integer('servicetypeid')->nullable()->index('servicetypeid');
            $table->integer('itemid')->nullable();
            $table->string('motornumber', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('mobile', 255)->nullable();
            $table->integer('total')->nullable();
            $table->integer('totalpaid')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('totaltax')->nullable();
            $table->integer('remain')->nullable();
            $table->integer('totalbefortax')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_invoice');
    }
};
