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
        Schema::table('model', function (Blueprint $table) {
            $table->foreign(['brand_id'], 'model_ibfk_1')->references(['id'])->on('brand')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['type_id'], 'model_ibfk_2')->references(['id'])->on('brand_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model', function (Blueprint $table) {
            $table->dropForeign('model_ibfk_1');
            $table->dropForeign('model_ibfk_2');
        });
    }
};
