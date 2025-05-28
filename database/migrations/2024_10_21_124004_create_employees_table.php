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
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('employee_name', 255)->nullable();
            $table->string('employee_address', 255)->nullable();
            $table->string('employee_national_id', 255)->nullable();
            $table->string('employee_phone', 255)->nullable();
            $table->string('employee_telephone', 255)->nullable();
            $table->decimal('employee_salary', 10)->nullable();
            $table->decimal('insurance_value', 10)->nullable();
            $table->decimal('employee_final_salary', 10)->nullable();
            $table->unsignedBigInteger('employee_role_id')->nullable()->index('employee_role_id');
            $table->tinyInteger('flag_finish_job')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('store_id')->nullable()->index('employees_ibfk_2');
            $table->decimal('raseed', 10)->nullable()->default(0);
            $table->string('accountant_number', 255);
            $table->string('solfa_accountant_number', 255);
            $table->string('commision_accountant_number', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
