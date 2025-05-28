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
        Schema::table('kit_part', function (Blueprint $table) {
            $table->foreign(['part_id'], 'kit_part_ibfk_1')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['kit_id'], 'kit_part_ibfk_2')->references(['id'])->on('kit')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kit_part', function (Blueprint $table) {
            $table->dropForeign('kit_part_ibfk_1');
            $table->dropForeign('kit_part_ibfk_2');
        });
    }
};
