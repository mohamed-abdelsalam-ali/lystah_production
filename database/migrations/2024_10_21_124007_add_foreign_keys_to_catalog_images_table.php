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
        Schema::table('catalog_images', function (Blueprint $table) {
            $table->foreign(['sub_group_id'], 'catalog_images_ibfk_1')->references(['id'])->on('sub_group')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_images', function (Blueprint $table) {
            $table->dropForeign('catalog_images_ibfk_1');
        });
    }
};
