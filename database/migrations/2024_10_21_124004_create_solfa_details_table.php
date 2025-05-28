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
        Schema::create('solfa_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('total', 10)->nullable();
            $table->decimal('amount', 10)->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->string('notes', 255)->nullable();
            $table->integer('employee_id')->nullable()->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solfa_details');
    }
};
