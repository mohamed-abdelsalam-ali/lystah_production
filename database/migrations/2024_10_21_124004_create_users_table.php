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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 32)->unique('username');
            $table->char('password', 128);
            $table->string('email', 120)->unique('email');
            $table->timestamp('created_on')->useCurrent();
            $table->string('telephone', 255)->nullable();
            $table->string('profile_img', 255)->nullable();
            $table->string('national_img', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
