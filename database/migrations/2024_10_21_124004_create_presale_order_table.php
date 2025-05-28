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
        Schema::create('presale_order', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('notes', 255)->nullable();
            $table->integer('flag')->nullable()->comment('0 جاري التجهيز
1 تم تجهيزها
2 تم التسليم
3 تم التحوبل لفاتورة
4 تم الالغاء');
            $table->string('img', 255)->nullable();
            $table->integer('client_id')->nullable()->index('client_id');
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->decimal('subtotal', 10)->nullable();
            $table->decimal('tax', 10)->nullable();
            $table->decimal('total', 10)->nullable();
            $table->integer('store_id')->nullable()->index('presale_order_ibfk_2');
            $table->string('location', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presale_order');
    }
};
