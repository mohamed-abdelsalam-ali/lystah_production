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
        Schema::table('kit_details', function (Blueprint $table) {
            $table->foreign(['kit_id'], 'kit_details_ibfk_1')->references(['id'])->on('kit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['kitpecs_id'], 'kit_details_ibfk_3')->references(['id'])->on('kit_specs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['unit_id'], 'kit_details_ibfk_2')->references(['id'])->on('mesure_unit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kit_details', function (Blueprint $table) {
            $table->dropForeign('kit_details_ibfk_1');
            $table->dropForeign('kit_details_ibfk_3');
            $table->dropForeign('kit_details_ibfk_2');
        });
    }
};
