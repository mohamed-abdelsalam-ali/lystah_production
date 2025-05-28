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
        Schema::create('equip', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->text('desc')->nullable();
            $table->string('name_eng', 255)->nullable()->default('0');
            $table->integer('year')->nullable()->index('type_id');
            $table->integer('hours')->nullable()->index('part_ibfk_5')->comment('ساعات التشغيل');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('tank_capacity')->nullable();
            $table->integer('limit_order')->nullable();
            $table->integer('flage_limit_order')->nullable()->default(0);
            $table->dateTime('insertion_date')->nullable();
            $table->date('last_sevice_date')->nullable();
            $table->integer('model_id')->nullable()->index('model_id');
            $table->integer('supplayer_id')->nullable()->index('supplayer_id');
            $table->integer('company_id')->nullable()->index('company_id');
            $table->integer('currency_id')->nullable()->index('currency_id');
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('buy_price')->nullable();
            $table->string('color', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equip');
    }
};
