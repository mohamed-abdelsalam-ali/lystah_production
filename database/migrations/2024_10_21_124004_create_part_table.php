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
        Schema::create('part', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('name_eng', 255)->nullable()->default('0');
            $table->dateTime('insertion_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('limit_order')->nullable();
            $table->integer('flage_limit_order')->nullable()->default(0);
            $table->integer('sub_group_id')->nullable()->index('tool_id')->comment('sub group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part');
    }
};
