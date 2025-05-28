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
        Schema::create('salary_employee_month', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('salary_month', 255)->nullable();
            $table->integer('employee_id')->nullable()->index('employee_id');
            $table->date('date')->nullable();
            $table->string('month', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_employee_month');
    }
};
