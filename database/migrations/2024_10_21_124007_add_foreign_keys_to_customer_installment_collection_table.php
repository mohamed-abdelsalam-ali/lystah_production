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
        Schema::table('customer_installment_collection', function (Blueprint $table) {
            $table->foreign(['invoice_installment_customer'], 'customer_installment_collection_ibfk_1')->references(['id'])->on('invoice_customer_installment')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_installment_collection', function (Blueprint $table) {
            $table->dropForeign('customer_installment_collection_ibfk_1');
        });
    }
};
