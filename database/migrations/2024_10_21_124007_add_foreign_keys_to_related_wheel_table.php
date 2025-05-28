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
        Schema::table('related_wheel', function (Blueprint $table) {
            $table->foreign(['wheel_id'], 'related_wheel_ibfk_1')->references(['id'])->on('wheel')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sug_wheel_id'], 'related_wheel_ibfk_2')->references(['id'])->on('wheel')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_wheel', function (Blueprint $table) {
            $table->dropForeign('related_wheel_ibfk_1');
            $table->dropForeign('related_wheel_ibfk_2');
        });
    }
};
