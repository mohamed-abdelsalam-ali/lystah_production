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
        Schema::table('related_tractor', function (Blueprint $table) {
            $table->foreign(['tractor_id'], 'related_tractor_ibfk_1')->references(['id'])->on('tractor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sug_tractor_id'], 'related_tractor_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_tractor', function (Blueprint $table) {
            $table->dropForeign('related_tractor_ibfk_1');
            $table->dropForeign('related_tractor_ibfk_2');
        });
    }
};
