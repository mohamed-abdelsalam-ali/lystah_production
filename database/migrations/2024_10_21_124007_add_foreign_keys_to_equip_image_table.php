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
        Schema::table('equip_image', function (Blueprint $table) {
            $table->foreign(['equip_id'], 'equip_image_ibfk_1')->references(['id'])->on('equip')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equip_image', function (Blueprint $table) {
            $table->dropForeign('equip_image_ibfk_1');
        });
    }
};
