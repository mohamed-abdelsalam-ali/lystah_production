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
        Schema::table('tractor_image', function (Blueprint $table) {
            $table->foreign(['tractor_id'], 'tractor_image_ibfk_1')->references(['id'])->on('tractor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tractor_image', function (Blueprint $table) {
            $table->dropForeign('tractor_image_ibfk_1');
        });
    }
};
