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
        Schema::create('kit_model', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('kit_id')->nullable()->index('kit_id');
            $table->integer('model_id')->nullable()->index('tool_id')->comment('series_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kit_model');
    }
};
