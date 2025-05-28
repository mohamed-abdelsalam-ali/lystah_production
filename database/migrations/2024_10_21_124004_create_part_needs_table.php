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
        Schema::create('part_needs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('part_id')->nullable()->index('part_id');
            $table->dateTime('insertion_date')->nullable();
            $table->string('notes', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_needs');
    }
};
