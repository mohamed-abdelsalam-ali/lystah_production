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
        Schema::create('solfa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->nullable()->index('employee_id');
            $table->string('total_solfa', 255)->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->string('notes', 255)->nullable();
            $table->string('finish_flag', 255)->nullable()->default('0');
            $table->decimal('remain', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solfa');
    }
};
