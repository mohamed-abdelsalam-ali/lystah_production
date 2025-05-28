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
        Schema::create('store', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel01', 255)->nullable();
            $table->string('tel02', 255)->nullable();
            $table->text('note')->nullable();
            $table->string('table_name', 255)->nullable();
            $table->integer('accountant_number')->default(0);
            $table->integer('safe_accountant_number')->default(0);
            $table->decimal('store_raseed', 10)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
};
