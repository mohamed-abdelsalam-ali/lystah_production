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
        Schema::create('related_equip', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('equip_id')->nullable()->index('part_id');
            $table->integer('sug_part_id')->nullable()->index('sug_part_id');
            $table->integer('part_types_id')->nullable()->default(1)->index('part_types_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_equip');
    }
};
