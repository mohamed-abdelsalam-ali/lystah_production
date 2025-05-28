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
        Schema::table('presale_order_tax', function (Blueprint $table) {
            $table->foreign(['presaleOrderid'], 'presale_order_tax_ibfk_1')->references(['id'])->on('presale_order')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['tax_id'], 'presale_order_tax_ibfk_2')->references(['id'])->on('taxes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presale_order_tax', function (Blueprint $table) {
            $table->dropForeign('presale_order_tax_ibfk_1');
            $table->dropForeign('presale_order_tax_ibfk_2');
        });
    }
};
