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
        Schema::create('sanad_sarf', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_sup_id')->nullable()->index('invoice_client_madyonea_ibfk_1');
            $table->dateTime('date')->nullable();
            $table->decimal('paied', 10, 0)->nullable();
            $table->text('note')->nullable();
            $table->integer('pyment_method')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->integer('type')->nullable()->default(0)->comment('1 client
2 supplier');
            $table->decimal('discount', 10, 0)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanad_sarf');
    }
};
