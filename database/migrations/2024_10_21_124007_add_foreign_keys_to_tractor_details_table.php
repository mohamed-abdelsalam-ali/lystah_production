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
        Schema::table('tractor_details', function (Blueprint $table) {
            $table->foreign(['tractor_id'], 'tractor_details_ibfk_1')->references(['id'])->on('tractor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['Tractorpecs_id'], 'tractor_details_ibfk_4')->references(['id'])->on('tractor_specs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['unit_id'], 'tractor_details_ibfk_3')->references(['id'])->on('mesure_unit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tractor_details', function (Blueprint $table) {
            $table->dropForeign('tractor_details_ibfk_1');
            $table->dropForeign('tractor_details_ibfk_4');
            $table->dropForeign('tractor_details_ibfk_3');
        });
    }
};
