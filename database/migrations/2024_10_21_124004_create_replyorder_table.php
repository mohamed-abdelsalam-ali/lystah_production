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
        Schema::create('replyorder', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_supplier_id')->nullable()->index('transaction_id');
            $table->integer('part_id')->nullable()->index('buypart_id');
            $table->decimal('price', 11, 5)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->default(0)->index('status_id')->comment('0 initial 1 from supplier to emara 2 from emara to supplier 3 confirmed');
            $table->string('note', 255)->nullable();
            $table->dateTime('creation_date')->nullable();
            $table->integer('quality_id')->nullable()->index('quality');
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
        Schema::dropIfExists('replyorder');
    }
};
