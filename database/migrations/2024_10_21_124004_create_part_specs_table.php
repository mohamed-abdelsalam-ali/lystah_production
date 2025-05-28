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
        Schema::create('part_specs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255);
            $table->integer('general_flag')->nullable()->default(0)->comment('لو القيمة 0 هتظهر معايا فى كل البارتس لكن لو عاوز اضيف مواصفات خاصة لقطعة معينة هيكون ال فلاج هوا id القطعة :)');
            $table->integer('type_id')->nullable()->default(1)->index('type_id');
            $table->integer('unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_specs');
    }
};
