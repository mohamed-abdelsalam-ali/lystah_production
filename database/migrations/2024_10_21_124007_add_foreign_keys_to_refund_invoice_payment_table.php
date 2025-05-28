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
        Schema::table('refund_invoice_payment', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'refund_invoice_payment_ibfk_1')->references(['id'])->on('invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_invoice_payment', function (Blueprint $table) {
            $table->dropForeign('refund_invoice_payment_ibfk_1');
        });
    }
};
