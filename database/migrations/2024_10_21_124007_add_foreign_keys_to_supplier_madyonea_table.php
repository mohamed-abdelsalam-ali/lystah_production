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
        Schema::table('supplier_madyonea', function (Blueprint $table) {
            $table->foreign(['supplier_id'], 'supplier_madyonea_ibfk_1')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_madyonea', function (Blueprint $table) {
            $table->dropForeign('supplier_madyonea_ibfk_1');
        });
    }
};
