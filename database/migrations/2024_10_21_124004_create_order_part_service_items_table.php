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
        Schema::create('order_part_service_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_part_service_id')->nullable()->index('order_part_service_id');
            $table->integer('part_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('type_id')->nullable()->index('order_part_service_items_ibfk_4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_part_service_items');
    }
};
