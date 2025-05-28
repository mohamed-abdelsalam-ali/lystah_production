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
        Schema::create('all_kit_part_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('all_kit_id')->nullable()->index('all_kit_id');
            $table->integer('part_id')->nullable()->index('part_id');
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('order_sup_id')->nullable()->index('order_sup_id');
            $table->string('note', 255)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_kit_part_item');
    }
};
