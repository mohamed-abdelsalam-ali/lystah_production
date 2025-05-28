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
        Schema::create('kit_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('kitpecs_id')->nullable()->index('Wheelpecs_id');
            $table->string('value', 255)->nullable();
            $table->integer('kit_id')->nullable()->index('wheel_id');
            $table->text('notes')->nullable();
            $table->integer('unit_id')->nullable()->index('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kit_details');
    }
};
