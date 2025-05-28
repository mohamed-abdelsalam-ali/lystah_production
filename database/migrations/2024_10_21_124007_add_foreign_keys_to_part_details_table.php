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
        Schema::table('part_details', function (Blueprint $table) {
            $table->foreign(['part_id'], 'part_details_ibfk_1')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['unit_id'], 'part_details_ibfk_3')->references(['id'])->on('mesure_unit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['partspecs_id'], 'part_details_ibfk_2')->references(['id'])->on('part_specs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_details', function (Blueprint $table) {
            $table->dropForeign('part_details_ibfk_1');
            $table->dropForeign('part_details_ibfk_3');
            $table->dropForeign('part_details_ibfk_2');
        });
    }
};
