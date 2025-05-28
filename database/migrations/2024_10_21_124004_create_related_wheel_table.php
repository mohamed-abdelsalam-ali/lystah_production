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
        Schema::create('related_wheel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('wheel_id')->nullable()->index('wheel_id');
            $table->integer('sug_wheel_id')->nullable()->index('sug_wheel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_wheel');
    }
};
