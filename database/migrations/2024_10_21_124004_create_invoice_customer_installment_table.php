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
        Schema::create('invoice_customer_installment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->integer('installment_type')->nullable()->index('installment_type');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->decimal('discount', 15, 3)->nullable();
            $table->integer('done')->nullable();
            $table->integer('customer_id')->nullable()->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_customer_installment');
    }
};
