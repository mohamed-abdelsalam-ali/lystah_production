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
        Schema::create('buy_part', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('transaction_id')->nullable()->index('transaction_id');
            $table->double('amount', 255, 0)->nullable();
            $table->integer('part_id')->nullable()->index('part_id');
            $table->double('delivered_amount', 255, 0)->nullable();
            $table->integer('part_types_id')->nullable()->index('part_types_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_part');
    }
};
