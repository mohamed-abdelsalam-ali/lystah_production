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
        Schema::create('offer_price_parts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('offer_price_id')->nullable()->index('offer_price_id');
            $table->integer('part_id')->nullable()->index('part_id');
            $table->integer('amount')->nullable();
            $table->decimal('price', 11, 0)->nullable();
            $table->string('p_number', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_price_parts');
    }
};
