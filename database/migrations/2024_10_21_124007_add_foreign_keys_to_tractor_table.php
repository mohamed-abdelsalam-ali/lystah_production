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
        Schema::table('tractor', function (Blueprint $table) {
            $table->foreign(['drive'], 'tractor_ibfk_8')->references(['id'])->on('drive')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['fronttire'], 'tractor_ibfk_10')->references(['id'])->on('wheel_dimension')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['model_id'], 'tractor_ibfk_12')->references(['id'])->on('series');
            $table->foreign(['gear_box'], 'tractor_ibfk_9')->references(['id'])->on('gearbox')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['reartire'], 'tractor_ibfk_11')->references(['id'])->on('wheel_dimension')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tractor', function (Blueprint $table) {
            $table->dropForeign('tractor_ibfk_8');
            $table->dropForeign('tractor_ibfk_10');
            $table->dropForeign('tractor_ibfk_12');
            $table->dropForeign('tractor_ibfk_9');
            $table->dropForeign('tractor_ibfk_11');
        });
    }
};
