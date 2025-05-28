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
        Schema::table('qayditem', function (Blueprint $table) {
            $table->foreign(['qaydid'], 'qayditem_ibfk_1')->references(['id'])->on('qayd')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['branchid'], 'qayditem_ibfk_2')->references(['id'])->on('branch_tree')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qayditem', function (Blueprint $table) {
            $table->dropForeign('qayditem_ibfk_1');
            $table->dropForeign('qayditem_ibfk_2');
        });
    }
};
