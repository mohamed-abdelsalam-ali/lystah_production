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
        Schema::table('salary_employee_month', function (Blueprint $table) {
            $table->foreign(['employee_id'], 'salary_employee_month_ibfk_1')->references(['id'])->on('employees')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_employee_month', function (Blueprint $table) {
            $table->dropForeign('salary_employee_month_ibfk_1');
        });
    }
};
