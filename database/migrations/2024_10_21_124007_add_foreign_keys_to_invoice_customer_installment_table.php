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
        Schema::table('invoice_customer_installment', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'invoice_customer_installment_ibfk_1')->references(['id'])->on('invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['customer_id'], 'invoice_customer_installment_ibfk_3')->references(['id'])->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['installment_type'], 'invoice_customer_installment_ibfk_2')->references(['id'])->on('installment')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_customer_installment', function (Blueprint $table) {
            $table->dropForeign('invoice_customer_installment_ibfk_1');
            $table->dropForeign('invoice_customer_installment_ibfk_3');
            $table->dropForeign('invoice_customer_installment_ibfk_2');
        });
    }
};
