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
        Schema::table('talef', function (Blueprint $table) {
            $table->foreign(['quality_id'], 'talef_ibfk_4')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'talef_ibfk_6')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'talef_ibfk_1')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'talef_ibfk_3')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['employee_id'], 'talef_ibfk_5')->references(['id'])->on('employees')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'talef_ibfk_2')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talef', function (Blueprint $table) {
            $table->dropForeign('talef_ibfk_4');
            $table->dropForeign('talef_ibfk_6');
            $table->dropForeign('talef_ibfk_1');
            $table->dropForeign('talef_ibfk_3');
            $table->dropForeign('talef_ibfk_5');
            $table->dropForeign('talef_ibfk_2');
        });
    }
};
