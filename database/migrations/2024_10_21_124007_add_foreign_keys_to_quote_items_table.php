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
        Schema::table('quote_items', function (Blueprint $table) {
            $table->foreign(['status_id'], 'quote_items_ibfk_4')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_type_id'], 'quote_items_ibfk_6')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sale_type'], 'quote_items_ibfk_1')->references(['id'])->on('pricing_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'quote_items_ibfk_3')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'quote_items_ibfk_5')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quote_id'], 'quote_items_ibfk_2')->references(['id'])->on('quote')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropForeign('quote_items_ibfk_4');
            $table->dropForeign('quote_items_ibfk_6');
            $table->dropForeign('quote_items_ibfk_1');
            $table->dropForeign('quote_items_ibfk_3');
            $table->dropForeign('quote_items_ibfk_5');
            $table->dropForeign('quote_items_ibfk_2');
        });
    }
};
