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
        Schema::create('invoice_all_part', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invoice_item_id')->nullable()->index('invoice_item_id');
            $table->integer('all_part_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('part_type_id')->nullable()->index('part_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_all_part');
    }
};
