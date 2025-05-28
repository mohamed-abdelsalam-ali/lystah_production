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
        Schema::create('supplierbank', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('IBAN', 255)->nullable();
            $table->string('accountNum', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplierbank');
    }
};
