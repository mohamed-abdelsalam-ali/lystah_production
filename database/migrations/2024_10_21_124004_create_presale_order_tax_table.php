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
        Schema::create('presale_order_tax', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('presaleOrderid')->nullable()->index('presaleOrderid');
            $table->integer('tax_id')->nullable()->index('tax_id');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presale_order_tax');
    }
};
