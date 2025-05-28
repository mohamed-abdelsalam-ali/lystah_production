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
        Schema::table('sale_pricing', function (Blueprint $table) {
            $table->foreign(['quality_id'], 'sale_pricing_ibfk_4')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'sale_pricing_ibfk_1')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'sale_pricing_ibfk_3')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['type_id'], 'sale_pricing_ibfk_5')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['currency_id'], 'sale_pricing_ibfk_2')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_pricing', function (Blueprint $table) {
            $table->dropForeign('sale_pricing_ibfk_4');
            $table->dropForeign('sale_pricing_ibfk_1');
            $table->dropForeign('sale_pricing_ibfk_3');
            $table->dropForeign('sale_pricing_ibfk_5');
            $table->dropForeign('sale_pricing_ibfk_2');
        });
    }
};
