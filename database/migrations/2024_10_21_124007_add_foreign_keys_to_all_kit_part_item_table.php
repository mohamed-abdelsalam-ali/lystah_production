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
        Schema::table('all_kit_part_item', function (Blueprint $table) {
            $table->foreign(['status_id'], 'all_kit_part_item_ibfk_4')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['order_sup_id'], 'all_kit_part_item_ibfk_6')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['all_kit_id'], 'all_kit_part_item_ibfk_1')->references(['id'])->on('all_kits')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'all_kit_part_item_ibfk_3')->references(['id'])->on('source')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'all_kit_part_item_ibfk_5')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_id'], 'all_kit_part_item_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('all_kit_part_item', function (Blueprint $table) {
            $table->dropForeign('all_kit_part_item_ibfk_4');
            $table->dropForeign('all_kit_part_item_ibfk_6');
            $table->dropForeign('all_kit_part_item_ibfk_1');
            $table->dropForeign('all_kit_part_item_ibfk_3');
            $table->dropForeign('all_kit_part_item_ibfk_5');
            $table->dropForeign('all_kit_part_item_ibfk_2');
        });
    }
};
