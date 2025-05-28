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
        Schema::table('store_structure', function (Blueprint $table) {
            $table->foreign(['store_id'], 'store_structure_ibfk_1')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_structure', function (Blueprint $table) {
            $table->dropForeign('store_structure_ibfk_1');
        });
    }
};
