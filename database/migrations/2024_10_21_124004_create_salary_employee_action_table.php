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
        Schema::create('salary_employee_action', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->nullable()->index('employee_id');
            $table->string('flag_type', 255)->nullable();
            $table->decimal('money', 10)->nullable();
            $table->date('date')->nullable();
            $table->string('finish_flag', 255)->nullable();
            $table->string('month', 255)->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_employee_action');
    }
};
