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
        Schema::create('stores_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('All_part_id')->nullable()->index('All_part_id');
            $table->integer('store_action_id')->nullable()->index('store_action_id');
            $table->integer('store_id')->nullable()->index('store_id');
            $table->integer('amount')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable()->comment('-2 hide  
-1 refused 
0 progress ammen ma5zan seeen this
1 admin approved  (exit)
2 need confirm
3 ameen confirmed (entrance)
4 ammen denied');
            $table->integer('type_id');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores_log');
    }
};
