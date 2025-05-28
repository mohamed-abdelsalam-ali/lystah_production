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
        Schema::table('clark_image', function (Blueprint $table) {
            $table->foreign(['clark_id'], 'clark_image_ibfk_1')->references(['id'])->on('clark')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clark_image', function (Blueprint $table) {
            $table->dropForeign('clark_image_ibfk_1');
        });
    }
};
