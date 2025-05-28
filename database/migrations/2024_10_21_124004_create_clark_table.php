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
        Schema::create('clark', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('eng_name', 255)->nullable()->default('');
            $table->string('desc', 255)->nullable();
            $table->string('motor_number', 255)->nullable();
            $table->string('clark_number', 255)->nullable();
            $table->string('hours', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->string('year', 255)->nullable();
            $table->integer('front_tire')->nullable()->index('clark_ibfk_8');
            $table->string('front_tire_status', 255)->nullable();
            $table->integer('rear_tire')->nullable()->index('clark_ibfk_9');
            $table->string('rear_tire_status', 255)->nullable();
            $table->string('tank', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('limit', 255)->nullable();
            $table->string('active_limit', 255)->nullable();
            $table->integer('supplayer_id')->nullable()->index('supplayer_id');
            $table->integer('company_id')->nullable()->index('clark_ibfk_3');
            $table->integer('currency_id')->nullable()->index('clark_ibfk_4');
            $table->integer('source_id')->nullable()->index('clark_ibfk_5');
            $table->integer('quality_id')->nullable()->index('clark_ibfk_6');
            $table->decimal('buy_price', 30, 0)->nullable();
            $table->integer('model_id')->nullable()->index('model_id');
            $table->string('power', 255)->nullable();
            $table->string('discs', 255)->nullable();
            $table->date('serivcedate')->nullable();
            $table->integer('gear_box')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clark');
    }
};
