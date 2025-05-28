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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreign(['status_id'], 'invoice_items_ibfk_7')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_type_id'], 'invoice_items_ibfk_9')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sale_type'], 'invoice_items_ibfk_4')->references(['id'])->on('pricing_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'invoice_items_ibfk_6')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'invoice_items_ibfk_8')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['invoice_id'], 'invoice_items_ibfk_5')->references(['id'])->on('invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign('invoice_items_ibfk_7');
            $table->dropForeign('invoice_items_ibfk_9');
            $table->dropForeign('invoice_items_ibfk_4');
            $table->dropForeign('invoice_items_ibfk_6');
            $table->dropForeign('invoice_items_ibfk_8');
            $table->dropForeign('invoice_items_ibfk_5');
        });
    }
};
