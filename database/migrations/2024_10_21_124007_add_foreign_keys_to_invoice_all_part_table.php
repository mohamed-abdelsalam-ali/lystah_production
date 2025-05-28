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
        Schema::table('invoice_all_part', function (Blueprint $table) {
            $table->foreign(['invoice_item_id'], 'invoice_all_part_ibfk_1')->references(['id'])->on('invoice_items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['part_type_id'], 'invoice_all_part_ibfk_2')->references(['id'])->on('part_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_all_part', function (Blueprint $table) {
            $table->dropForeign('invoice_all_part_ibfk_1');
            $table->dropForeign('invoice_all_part_ibfk_2');
        });
    }
};
