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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('date')->nullable();
            $table->integer('part_id')->nullable()->index('sale_pricing_id');
            $table->double('amount', 11, 0)->nullable();
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('part_type_id')->nullable()->index('part_type_id');
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->integer('sale_type')->nullable()->index('sale_type');
            $table->decimal('discount', 10)->nullable()->default(0);
            $table->decimal('over_price', 10)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
