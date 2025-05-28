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
        Schema::create('refund_invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->integer('item_id')->nullable()->comment('all_part_id');
            $table->integer('r_amount')->nullable();
            $table->string('notes', 0)->nullable();
            $table->dateTime('date')->nullable();
            $table->decimal('item_price', 10, 0)->nullable()->default(0);
            $table->decimal('r_tax', 10, 0)->default(0);
            $table->integer('r_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_invoice');
    }
};
