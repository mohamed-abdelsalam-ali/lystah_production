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
        Schema::create('sale_pricing', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('part_id')->nullable()->index('part_id');
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('currency_id')->nullable()->index('currency_id');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->integer('sale_type')->nullable()->index('sale_type');
            $table->text('desc')->nullable();
            $table->integer('status_id')->nullable()->index('status_id');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->decimal('price', 14)->nullable();
            $table->integer('type_id')->nullable()->index('type_id');
            $table->integer('group_flag')->nullable()->default(0)->comment('0 not group
1 group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_pricing');
    }
};
