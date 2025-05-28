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
        Schema::table('sale_type_ratio', function (Blueprint $table) {
            $table->foreign(['sale_type_id'], 'sale_type_ratio_ibfk_1')->references(['id'])->on('pricing_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'sale_type_ratio_ibfk_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_type_ratio', function (Blueprint $table) {
            $table->dropForeign('sale_type_ratio_ibfk_1');
            $table->dropForeign('sale_type_ratio_ibfk_2');
        });
    }
};
