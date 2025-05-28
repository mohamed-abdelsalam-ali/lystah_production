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
        Schema::table('offer_price', function (Blueprint $table) {
            $table->foreign(['client_id'], 'offer_price_ibfk_4')->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['sell_type_id'], 'offer_price_ibfk_1')->references(['id'])->on('pricing_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['company_id'], 'offer_price_ibfk_3')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['currency_id'], 'offer_price_ibfk_2')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_price', function (Blueprint $table) {
            $table->dropForeign('offer_price_ibfk_4');
            $table->dropForeign('offer_price_ibfk_1');
            $table->dropForeign('offer_price_ibfk_3');
            $table->dropForeign('offer_price_ibfk_2');
        });
    }
};
