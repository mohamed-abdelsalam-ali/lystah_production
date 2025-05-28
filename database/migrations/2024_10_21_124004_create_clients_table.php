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
        Schema::create('clients', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tel01', 255)->nullable();
            $table->string('tel02', 255)->nullable();
            $table->string('tel03', 255)->nullable();
            $table->string('national_no', 255)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('client_img', 255)->nullable();
            $table->string('email1', 255)->nullable();
            $table->string('email2', 255)->nullable();
            $table->string('segl_togary', 255)->nullable();
            $table->string('betaa_darebia', 255)->nullable();
            $table->integer('sup_id')->nullable();
            $table->double('client_raseed')->nullable();
            $table->integer('status')->default(0)->comment('0 default 1- block');
            $table->integer('currency_id')->nullable()->index('cfk12');
            $table->integer('accountant_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
