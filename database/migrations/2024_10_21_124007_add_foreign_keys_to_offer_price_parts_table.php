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
        Schema::table('offer_price_parts', function (Blueprint $table) {
            $table->foreign(['offer_price_id'], 'offer_price_parts_ibfk_1')->references(['id'])->on('offer_price')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_id'], 'offer_price_parts_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_price_parts', function (Blueprint $table) {
            $table->dropForeign('offer_price_parts_ibfk_1');
            $table->dropForeign('offer_price_parts_ibfk_2');
        });
    }
};
