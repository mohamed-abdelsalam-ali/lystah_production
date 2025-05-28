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
        Schema::create('sale_type_ratio', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sale_type_id')->nullable()->index('sale_type_id');
            $table->decimal('value', 10, 0)->nullable();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->integer('type')->nullable();
            $table->string('notes', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_type_ratio');
    }
};
