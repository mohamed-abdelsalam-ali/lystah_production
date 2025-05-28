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
        Schema::create('customer_installment_collection', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('date')->nullable();
            $table->decimal('paied_value', 15)->nullable();
            $table->integer('invoice_installment_customer')->nullable()->index('customer_installment_collection_ibfk_1');
            $table->string('notes', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_installment_collection');
    }
};
