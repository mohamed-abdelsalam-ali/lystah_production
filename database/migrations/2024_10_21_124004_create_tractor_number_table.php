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
        Schema::create('tractor_number', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tractor_id')->nullable()->index('tractor_id');
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->string('number', 255)->nullable();
            $table->integer('flag_OEM')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tractor_number');
    }
};
