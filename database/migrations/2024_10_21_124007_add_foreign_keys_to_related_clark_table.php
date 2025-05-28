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
        Schema::table('related_clark', function (Blueprint $table) {
            $table->foreign(['clark_id'], 'related_clark_ibfk_1')->references(['id'])->on('clark')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_types_id'], 'related_clark_ibfk_3')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sug_part_id'], 'related_clark_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_clark', function (Blueprint $table) {
            $table->dropForeign('related_clark_ibfk_1');
            $table->dropForeign('related_clark_ibfk_3');
            $table->dropForeign('related_clark_ibfk_2');
        });
    }
};
