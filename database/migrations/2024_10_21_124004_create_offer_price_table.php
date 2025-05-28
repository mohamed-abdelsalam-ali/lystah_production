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
        Schema::create('offer_price', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('sell_type_id')->nullable()->index('sell_type_id');
            $table->integer('currency_id')->nullable()->index('currency_id');
            $table->integer('company_id')->nullable()->index('company_id');
            $table->integer('client_id')->nullable()->index('client_id');
            $table->string('delevery_place', 255)->nullable();
            $table->string('check_owner', 255)->nullable();
            $table->integer('status_flage')->nullable()->default(1)->comment('0 : offer ended
1: offer still avilable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_price');
    }
};
