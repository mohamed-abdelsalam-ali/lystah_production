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
        Schema::create('client_discount', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_id')->nullable()->index('client_id');
            $table->double('discount', 11, 0)->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
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
        Schema::dropIfExists('client_discount');
    }
};
