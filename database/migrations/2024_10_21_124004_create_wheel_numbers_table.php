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
        Schema::create('wheel_numbers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('number', 255)->nullable();
            $table->integer('wheel_id')->nullable()->index('wheel_id');
            $table->integer('flag_OM')->nullable();
            $table->integer('supplier_id')->nullable()->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_numbers');
    }
};
