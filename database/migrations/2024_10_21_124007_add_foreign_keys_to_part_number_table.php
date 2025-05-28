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
        Schema::table('part_number', function (Blueprint $table) {
            $table->foreign(['part_id'], 'part_number_ibfk_2')->references(['id'])->on('part')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplier_id'], 'part_number_ibfk_3')->references(['id'])->on('supplier')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_number', function (Blueprint $table) {
            $table->dropForeign('part_number_ibfk_2');
            $table->dropForeign('part_number_ibfk_3');
        });
    }
};
