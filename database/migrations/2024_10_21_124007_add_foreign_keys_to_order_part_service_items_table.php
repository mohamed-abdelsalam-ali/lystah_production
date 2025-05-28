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
        Schema::table('order_part_service_items', function (Blueprint $table) {
            $table->foreign(['type_id'], 'order_part_service_items_ibfk_4')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'order_part_service_items_ibfk_1')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'order_part_service_items_ibfk_3')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['order_part_service_id'], 'order_part_service_items_ibfk_5')->references(['id'])->on('order_part_service')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'order_part_service_items_ibfk_2')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_part_service_items', function (Blueprint $table) {
            $table->dropForeign('order_part_service_items_ibfk_4');
            $table->dropForeign('order_part_service_items_ibfk_1');
            $table->dropForeign('order_part_service_items_ibfk_3');
            $table->dropForeign('order_part_service_items_ibfk_5');
            $table->dropForeign('order_part_service_items_ibfk_2');
        });
    }
};
