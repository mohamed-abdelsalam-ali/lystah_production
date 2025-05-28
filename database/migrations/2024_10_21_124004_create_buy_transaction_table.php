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
        Schema::create('buy_transaction', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('date')->nullable();
            $table->integer('company_id')->nullable()->index('company_id');
            $table->text('desc')->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('final')->default(0)->comment('0 init
1 Deleted');
            $table->dateTime('creation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_transaction');
    }
};
