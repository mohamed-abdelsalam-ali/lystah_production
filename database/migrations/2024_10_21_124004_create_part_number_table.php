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
        Schema::create('part_number', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('number', 255)->nullable();
            $table->integer('flag_OM')->nullable()->default(0)->comment('0 not OM
1 OM');
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->integer('part_id')->nullable()->index('part_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_number');
    }
};
