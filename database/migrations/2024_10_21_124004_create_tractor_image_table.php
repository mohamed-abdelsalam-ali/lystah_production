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
        Schema::create('tractor_image', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('url', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('desc', 255)->nullable();
            $table->integer('tractor_id')->nullable()->index('tractor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tractor_image');
    }
};
