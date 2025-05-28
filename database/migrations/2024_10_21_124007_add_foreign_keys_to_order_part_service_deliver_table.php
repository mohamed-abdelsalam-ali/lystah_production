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
        Schema::table('order_part_service_deliver', function (Blueprint $table) {
            $table->foreign(['pricing_type_id'], 'order_part_service_deliver_ibfk_4')->references(['id'])->on('pricing_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['type_id'], 'order_part_service_deliver_ibfk_1')->references(['id'])->on('type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'order_part_service_deliver_ibfk_3')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'order_part_service_deliver_ibfk_2')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_part_service_deliver', function (Blueprint $table) {
            $table->dropForeign('order_part_service_deliver_ibfk_4');
            $table->dropForeign('order_part_service_deliver_ibfk_1');
            $table->dropForeign('order_part_service_deliver_ibfk_3');
            $table->dropForeign('order_part_service_deliver_ibfk_2');
        });
    }
};
