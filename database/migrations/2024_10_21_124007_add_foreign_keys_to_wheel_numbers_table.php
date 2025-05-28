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
        Schema::table('wheel_numbers', function (Blueprint $table) {
            $table->foreign(['wheel_id'], 'wheel_numbers_ibfk_1')->references(['id'])->on('wheel')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplier_id'], 'wheel_numbers_ibfk_2')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wheel_numbers', function (Blueprint $table) {
            $table->dropForeign('wheel_numbers_ibfk_1');
            $table->dropForeign('wheel_numbers_ibfk_2');
        });
    }
};
