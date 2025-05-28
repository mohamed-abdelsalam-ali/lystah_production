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
        Schema::create('qayd_deleted', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->integer('qaydtypeid')->nullable()->index('qaydtypeid');
            $table->integer('accountnumber')->nullable();
            $table->date('date')->nullable();
            $table->string('note', 255)->nullable();
            $table->date('date_created')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qayd_deleted');
    }
};
