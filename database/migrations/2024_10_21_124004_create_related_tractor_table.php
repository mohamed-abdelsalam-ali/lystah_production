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
        Schema::create('related_tractor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tractor_id')->nullable()->index('tractor_id');
            $table->integer('sug_tractor_id')->nullable()->index('sug_tractor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_tractor');
    }
};
