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
        Schema::create('wheel_image', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('wheel_id')->index('wheel_id');
            $table->string('image_name', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('desc', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_image');
    }
};
