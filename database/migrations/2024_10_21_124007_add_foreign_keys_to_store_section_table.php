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
        Schema::table('store_section', function (Blueprint $table) {
            $table->foreign(['status_id'], 'store_section_ibfk_5')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['section_id'], 'store_section_ibfk_7')->references(['id'])->on('store_structure')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'store_section_ibfk_1')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'store_section_ibfk_4')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'store_section_ibfk_6')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['order_supplier_id'], 'store_section_ibfk_8')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['type_id'], 'store_section_ibfk_2')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_section', function (Blueprint $table) {
            $table->dropForeign('store_section_ibfk_5');
            $table->dropForeign('store_section_ibfk_7');
            $table->dropForeign('store_section_ibfk_1');
            $table->dropForeign('store_section_ibfk_4');
            $table->dropForeign('store_section_ibfk_6');
            $table->dropForeign('store_section_ibfk_8');
            $table->dropForeign('store_section_ibfk_2');
        });
    }
};
