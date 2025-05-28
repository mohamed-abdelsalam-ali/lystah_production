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
        Schema::create('order_supplier', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('transaction_id')->nullable()->index('transaction_id');
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->string('send_mail', 255)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('status', 255)->nullable()->comment('0 init
1 send
2 supplier_see_it
3 تم الرد
4 finish');
            $table->dateTime('deliver_date')->nullable();
            $table->integer('currency_id')->nullable()->default(367)->index('currency_id');
            $table->decimal('total_price', 10)->nullable();
            $table->string('bank_account', 255)->nullable();
            $table->double('container_size')->nullable();
            $table->dateTime('confirmation_date')->nullable();
            $table->string('image_url', 255)->nullable()->comment('صورة الفاتورة ان وجدت');
            $table->decimal('paied', 10, 0)->nullable();
            $table->date('due_date')->nullable();
            $table->integer('payment')->default(0)->comment('0 كاش');
            $table->decimal('tax', 11, 0)->nullable()->default(0);
            $table->decimal('pricebeforeTax', 10, 0)->default(0);
            $table->double('transport_coast')->nullable()->default(0);
            $table->double('insurant_coast')->nullable()->default(0);
            $table->double('customs_coast')->nullable()->default(0);
            $table->double('commotion_coast')->nullable()->default(0);
            $table->double('other_coast')->nullable()->default(0);
            $table->double('coast')->nullable()->default(0);
            $table->string('taxInvolved_flag', 255)->nullable();
            $table->string('taxkasmInvolved_flag', 255)->nullable();
            $table->unsignedInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_supplier');
    }
};
