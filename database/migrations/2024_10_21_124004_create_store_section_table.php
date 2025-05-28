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
        Schema::create('store_section', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('store_id')->nullable()->index('store_id');
            $table->integer('section_id')->nullable()->index('section_id');
            $table->integer('order_supplier_id')->nullable()->index('order_supplier_id');
            $table->integer('type_id')->nullable()->index('type_id');
            $table->integer('part_id')->nullable()->index('part_id');
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->double('amount')->nullable();
            $table->string('notes', 255)->nullable();
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
        Schema::dropIfExists('store_section');
    }
};
