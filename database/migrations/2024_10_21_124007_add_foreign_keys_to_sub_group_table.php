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
        Schema::table('sub_group', function (Blueprint $table) {
            $table->foreign(['group_id'], 'sub_group_ibfk_1')->references(['id'])->on('group')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_group', function (Blueprint $table) {
            $table->dropForeign('sub_group_ibfk_1');
        });
    }
};
