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
        Schema::create('all_clarks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('part_id')->nullable()->index('part_id');
            $table->integer('order_supplier_id')->nullable()->index('order_supplier_id');
            $table->double('amount', 11, 2)->nullable();
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->decimal('buy_price', 10)->nullable();
            $table->dateTime('insertion_date')->nullable();
            $table->string('remain_amount', 255)->nullable();
            $table->integer('flag')->nullable()->default(0)->comment('0 in
1 ended from talab3a');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->dateTime('lastupdate')->nullable();
            $table->double('buy_costing')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_clarks');
    }
};
