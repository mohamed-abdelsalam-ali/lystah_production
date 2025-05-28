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
        Schema::table('qayd_deleted', function (Blueprint $table) {
            $table->foreign(['qaydtypeid'], 'qayd_deleted_ibfk_1')->references(['id'])->on('qaydtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qayd_deleted', function (Blueprint $table) {
            $table->dropForeign('qayd_deleted_ibfk_1');
        });
    }
};
