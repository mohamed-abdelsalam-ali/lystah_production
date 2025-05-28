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
        Schema::table('qayditem_deleted', function (Blueprint $table) {
            $table->foreign(['branchid'], 'qayditem_deleted_ibfk_2')->references(['id'])->on('branch_tree')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qayditem_deleted', function (Blueprint $table) {
            $table->dropForeign('qayditem_deleted_ibfk_2');
        });
    }
};
