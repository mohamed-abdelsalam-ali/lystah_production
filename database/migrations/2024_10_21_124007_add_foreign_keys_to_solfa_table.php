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
        Schema::table('solfa', function (Blueprint $table) {
            $table->foreign(['employee_id'], 'solfa_ibfk_1')->references(['id'])->on('employees')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'solfa_ibfk_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solfa', function (Blueprint $table) {
            $table->dropForeign('solfa_ibfk_1');
            $table->dropForeign('solfa_ibfk_2');
        });
    }
};
