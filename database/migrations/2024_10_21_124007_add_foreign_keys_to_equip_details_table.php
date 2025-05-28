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
        Schema::table('equip_details', function (Blueprint $table) {
            $table->foreign(['equip_id'], 'equip_details_ibfk_1')->references(['id'])->on('equip')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['unit_id'], 'equip_details_ibfk_3')->references(['id'])->on('mesure_unit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['equipspecs_id'], 'equip_details_ibfk_2')->references(['id'])->on('equip_specs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equip_details', function (Blueprint $table) {
            $table->dropForeign('equip_details_ibfk_1');
            $table->dropForeign('equip_details_ibfk_3');
            $table->dropForeign('equip_details_ibfk_2');
        });
    }
};
