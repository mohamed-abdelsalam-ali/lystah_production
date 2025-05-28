<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('telephone')->nullable();
                $table->string('profile_img')->nullable();
                $table->string('national_img')->nullable();
                $table->string('company_name')->nullable();
                $table->string('db_name')->nullable();
                $table->timestamp('created_on')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('role')->default('user'); // admin, user
                $table->boolean('is_active')->default(true);
                $table->json('settings')->nullable();
                $table->rememberToken();
                $table->timestamps();

                $table->index('username');
                $table->index('email');
                $table->index('role');
                $table->index('is_active');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
