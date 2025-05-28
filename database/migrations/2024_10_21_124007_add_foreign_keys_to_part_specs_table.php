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
        Schema::table('part_specs', function (Blueprint $table) {
            $table->foreign(['type_id'], 'part_specs_ibfk_1')->references(['id'])->on('part_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_specs', function (Blueprint $table) {
            $table->dropForeign('part_specs_ibfk_1');
        });
    }
};
