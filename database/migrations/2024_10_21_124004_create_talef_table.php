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
        Schema::create('talef', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('date')->nullable();
            $table->integer('store_id')->nullable()->index('store_id');
            $table->integer('part_id')->nullable();
            $table->integer('source_id')->nullable()->index('source_id');
            $table->integer('status_id')->nullable()->index('talef_ibfk_3');
            $table->integer('quality_id')->nullable()->index('quality_id');
            $table->integer('type_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('employee_id')->nullable()->index('employee_id');
            $table->text('notes')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('talef_ibfk_6');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talef');
    }
};
