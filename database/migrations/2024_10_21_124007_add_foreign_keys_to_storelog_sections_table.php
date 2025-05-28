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
        Schema::table('storelog_sections', function (Blueprint $table) {
            $table->foreign(['store_log_id'], 'storelog_sections_ibfk_1')->references(['id'])->on('stores_log')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'storelog_sections_ibfk_3')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['section_id'], 'storelog_sections_ibfk_2')->references(['id'])->on('store_structure')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storelog_sections', function (Blueprint $table) {
            $table->dropForeign('storelog_sections_ibfk_1');
            $table->dropForeign('storelog_sections_ibfk_3');
            $table->dropForeign('storelog_sections_ibfk_2');
        });
    }
};
