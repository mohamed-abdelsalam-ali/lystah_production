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
        Schema::create('damaged_parts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('all_part_id')->nullable()->index('part_id');
            $table->double('amount')->nullable();
            $table->integer('supplier_order_id')->nullable()->index('supplier_order_id');
            $table->string('notes', 255)->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('store_log_id')->nullable()->index('store1_ibfk_251');
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damaged_parts');
    }
};
