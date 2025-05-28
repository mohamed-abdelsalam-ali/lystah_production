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
        Schema::create('qayditem_deleted', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->integer('qaydid')->nullable()->index('qaydid');
            $table->integer('branchid')->nullable()->index('branchid');
            $table->decimal('dayin', 11, 3)->nullable();
            $table->decimal('madin', 11, 3)->nullable();
            $table->string('topic', 255)->nullable();
            $table->integer('invoiceid')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('qayditem_deleted');
    }
};
