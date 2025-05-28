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
        Schema::create('catalog_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sub_group_id')->nullable()->index('sub_group_id');
            $table->string('image_url', 255);
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
        Schema::dropIfExists('catalog_images');
    }
};
