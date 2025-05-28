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
        Schema::create('efrag_image', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('buyer_name', 255)->nullable();
            $table->string('merror_center', 255)->nullable();
            $table->string('desc', 255)->nullable();
            $table->string('image_name', 255)->nullable();
            $table->integer('tractor_id')->nullable()->index('part_id');
            $table->integer('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('efrag_image');
    }
};
