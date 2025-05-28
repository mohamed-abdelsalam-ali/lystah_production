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
        Schema::table('related_part', function (Blueprint $table) {
            $table->foreign(['part_id'], 'related_part_ibfk_1')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['part_types_id'], 'related_part_ibfk_3')->references(['id'])->on('part_types')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sug_part_id'], 'related_part_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_part', function (Blueprint $table) {
            $table->dropForeign('related_part_ibfk_1');
            $table->dropForeign('related_part_ibfk_3');
            $table->dropForeign('related_part_ibfk_2');
        });
    }
};
