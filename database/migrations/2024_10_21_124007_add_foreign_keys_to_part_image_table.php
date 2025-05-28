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
        Schema::table('part_image', function (Blueprint $table) {
            $table->foreign(['part_id'], 'part_image_ibfk_1')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_image', function (Blueprint $table) {
            $table->dropForeign('part_image_ibfk_1');
        });
    }
};
