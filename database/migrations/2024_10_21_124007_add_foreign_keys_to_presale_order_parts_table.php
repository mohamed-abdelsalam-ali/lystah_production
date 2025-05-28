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
        Schema::table('presale_order_parts', function (Blueprint $table) {
            $table->foreign(['quality_id'], 'presale_order_parts_ibfk_4')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['presaleOrder_id'], 'presale_order_parts_ibfk_1')->references(['id'])->on('presale_order')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'presale_order_parts_ibfk_3')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_type_id'], 'presale_order_parts_ibfk_5')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'presale_order_parts_ibfk_2')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presale_order_parts', function (Blueprint $table) {
            $table->dropForeign('presale_order_parts_ibfk_4');
            $table->dropForeign('presale_order_parts_ibfk_1');
            $table->dropForeign('presale_order_parts_ibfk_3');
            $table->dropForeign('presale_order_parts_ibfk_5');
            $table->dropForeign('presale_order_parts_ibfk_2');
        });
    }
};
