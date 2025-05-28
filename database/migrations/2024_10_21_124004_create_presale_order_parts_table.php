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
        Schema::create('presale_order_parts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('part_id')->nullable()->index('part_id');
            $table->string('notes', 255)->nullable();
            $table->integer('amount')->nullable()->default(0)->comment('init');
            $table->integer('presaleOrder_id')->nullable()->index('presaleOrder_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('part_type_id')->nullable()->index('presale_order_parts_ibfk_6');
            $table->decimal('price', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presale_order_parts');
    }
};
