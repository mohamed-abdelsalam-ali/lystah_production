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
        Schema::create('refund_invoice_payment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->float('total_paied', 10, 0)->nullable()->comment('المبلغ المستحق رده');
            $table->float('paied', 10, 0)->nullable()->comment('المبلغ المردود شامل الضريبة والخصم');
            $table->float('total_dicount', 10, 0)->nullable();
            $table->float('total_tax', 10, 0)->nullable();
            $table->text('desc')->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->integer('payment_acountant')->nullable();
            $table->integer('payment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_invoice_payment');
    }
};
