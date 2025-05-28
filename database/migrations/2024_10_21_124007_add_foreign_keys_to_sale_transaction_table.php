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
        Schema::table('sale_transaction', function (Blueprint $table) {
            $table->foreign(['sale_type'], 'sale_transaction_ibfk_6')->references(['id'])->on('sale_type')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['buy_company_id'], 'sale_transaction_ibfk_2')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['store_id'], 'sale_transaction_ibfk_5')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['invoice_id'], 'sale_transaction_ibfk_7')->references(['id'])->on('invoice')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['client_id'], 'sale_transaction_ibfk_3')->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_transaction', function (Blueprint $table) {
            $table->dropForeign('sale_transaction_ibfk_6');
            $table->dropForeign('sale_transaction_ibfk_2');
            $table->dropForeign('sale_transaction_ibfk_5');
            $table->dropForeign('sale_transaction_ibfk_7');
            $table->dropForeign('sale_transaction_ibfk_3');
        });
    }
};
