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
        Schema::create('tractor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('name_en', 255)->nullable()->default('0');
            $table->integer('fronttire')->nullable()->index('fronttire');
            $table->integer('gear_box')->nullable()->index('gear_box');
            $table->string('hours', 255)->nullable();
            $table->string('power', 255)->nullable();
            $table->integer('reartire')->nullable()->index('reartire');
            $table->integer('year')->nullable();
            $table->string('color', 255)->nullable();
            $table->string('tank_capacity', 255)->nullable();
            $table->string('discs', 255)->nullable();
            $table->string('tractor_number', 255)->nullable()->comment('رقم الشاسية');
            $table->integer('model_id')->nullable()->index('category_id');
            $table->string('desc', 255)->nullable();
            $table->dateTime('insertion_date')->nullable();
            $table->integer('drive')->nullable()->index('drive');
            $table->integer('fronttirestatus')->nullable();
            $table->integer('reartirestatus')->nullable();
            $table->string('motornumber', 255)->nullable();
            $table->date('serivcedate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tractor');
    }
};
