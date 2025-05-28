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
        Schema::create('part_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('partspecs_id')->nullable()->index('partspecs_id');
            $table->string('value', 255)->nullable();
            $table->integer('part_id')->nullable()->index('part_id');
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
        Schema::dropIfExists('part_details');
    }
};
