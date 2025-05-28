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
        Schema::table('wheel', function (Blueprint $table) {
            $table->foreign(['model_id'], 'wheel_ibfk_5')->references(['id'])->on('wheel_model')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['type_id'], 'wheel_ibfk_10')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['dimension'], 'wheel_ibfk_3')->references(['id'])->on('wheel_dimension')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'wheel_ibfk_8')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['wheel_material_id'], 'wheel_ibfk_11')->references(['id'])->on('wheel_material')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wheel', function (Blueprint $table) {
            $table->dropForeign('wheel_ibfk_5');
            $table->dropForeign('wheel_ibfk_10');
            $table->dropForeign('wheel_ibfk_3');
            $table->dropForeign('wheel_ibfk_8');
            $table->dropForeign('wheel_ibfk_11');
        });
    }
};
