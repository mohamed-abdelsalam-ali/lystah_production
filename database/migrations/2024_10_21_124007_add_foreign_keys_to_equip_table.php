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
        Schema::table('equip', function (Blueprint $table) {
            $table->foreign(['currency_id'], 'equip_ibfk_5')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'equip_ibfk_7')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'equip_ibfk_1')->references(['id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['company_id'], 'equip_ibfk_4')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['source_id'], 'equip_ibfk_6')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['model_id'], 'equip_ibfk_8')->references(['id'])->on('series')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplayer_id'], 'equip_ibfk_3')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equip', function (Blueprint $table) {
            $table->dropForeign('equip_ibfk_5');
            $table->dropForeign('equip_ibfk_7');
            $table->dropForeign('equip_ibfk_1');
            $table->dropForeign('equip_ibfk_4');
            $table->dropForeign('equip_ibfk_6');
            $table->dropForeign('equip_ibfk_8');
            $table->dropForeign('equip_ibfk_3');
        });
    }
};
