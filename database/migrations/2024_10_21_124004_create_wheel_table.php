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
        Schema::create('wheel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->integer('dimension')->nullable()->index('dimension');
            $table->string('description', 255)->nullable();
            $table->integer('type_id')->nullable()->index('type_id');
            $table->integer('status_id')->nullable()->default(1)->index('status_id');
            $table->dateTime('insertion_date')->nullable();
            $table->string('name_eng', 255)->nullable()->default('0');
            $table->integer('limit_order')->nullable();
            $table->integer('flage_limit_order')->nullable();
            $table->integer('model_id')->nullable()->index('model_id');
            $table->integer('wheel_material_id')->nullable()->index('wheel_material_id');
            $table->integer('tt_tl')->nullable()->default(1)->comment('tt =1 .... And .....tl =2');
            $table->float('wheel_container_size', 11)->nullable()->default(0)->comment('size in percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel');
    }
};
