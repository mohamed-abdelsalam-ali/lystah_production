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
        Schema::create('order_part_service', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('equips_id')->nullable();
            $table->integer('type_id')->nullable()->index('order_part_service_ibfk_1');
            $table->integer('store_id')->nullable()->index('store_id');
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->dateTime('date')->nullable();
            $table->string('notes', 255)->nullable();
            $table->integer('flag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_part_service');
    }
};
