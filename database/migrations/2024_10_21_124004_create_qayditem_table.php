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
        Schema::create('qayditem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('qaydid')->nullable()->index('qaydid');
            $table->integer('branchid')->nullable()->index('branchid');
            $table->decimal('dayin', 11, 3)->nullable();
            $table->decimal('madin', 11, 3)->nullable();
            $table->string('topic', 255)->nullable();
            $table->integer('invoiceid')->nullable();
            $table->date('date')->nullable();
            $table->integer('flag')->nullable()->comment('0 = buyInv     1= sellInv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qayditem');
    }
};
