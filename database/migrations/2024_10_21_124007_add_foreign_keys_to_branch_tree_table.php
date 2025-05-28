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
        Schema::table('branch_tree', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'branch_tree_ibfk_1')->references(['id'])->on('branch_tree')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['branch_type'], 'branch_tree_ibfk_2')->references(['id'])->on('branch_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_tree', function (Blueprint $table) {
            $table->dropForeign('branch_tree_ibfk_1');
            $table->dropForeign('branch_tree_ibfk_2');
        });
    }
};
