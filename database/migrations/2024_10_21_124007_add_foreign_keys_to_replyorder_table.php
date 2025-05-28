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
        Schema::table('replyorder', function (Blueprint $table) {
            $table->foreign(['quality_id'], 'replyorder_ibfk_5')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['order_supplier_id'], 'replyorder_ibfk_1')->references(['id'])->on('order_supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'replyorder_ibfk_4')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_type_id'], 'replyorder_ibfk_6')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'replyorder_ibfk_3')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replyorder', function (Blueprint $table) {
            $table->dropForeign('replyorder_ibfk_5');
            $table->dropForeign('replyorder_ibfk_1');
            $table->dropForeign('replyorder_ibfk_4');
            $table->dropForeign('replyorder_ibfk_6');
            $table->dropForeign('replyorder_ibfk_3');
        });
    }
};
