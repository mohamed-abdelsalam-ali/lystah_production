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
        Schema::create('kit_number', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('number', 255)->nullable();
            $table->integer('flag_OM')->nullable();
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->integer('kit_id')->nullable()->index('kit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kit_number');
    }
};
