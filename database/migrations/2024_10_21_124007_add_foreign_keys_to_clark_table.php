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
        Schema::table('clark', function (Blueprint $table) {
            $table->foreign(['source_id'], 'clark_ibfk_5')->references(['id'])->on('source')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['model_id'], 'clark_ibfk_7')->references(['id'])->on('series')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['rear_tire'], 'clark_ibfk_9')->references(['id'])->on('wheel_dimension')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplayer_id'], 'clark_ibfk_2')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['currency_id'], 'clark_ibfk_4')->references(['id'])->on('currency_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quality_id'], 'clark_ibfk_6')->references(['id'])->on('part_quality')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['front_tire'], 'clark_ibfk_8')->references(['id'])->on('wheel_dimension')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['company_id'], 'clark_ibfk_3')->references(['id'])->on('company')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clark', function (Blueprint $table) {
            $table->dropForeign('clark_ibfk_5');
            $table->dropForeign('clark_ibfk_7');
            $table->dropForeign('clark_ibfk_9');
            $table->dropForeign('clark_ibfk_2');
            $table->dropForeign('clark_ibfk_4');
            $table->dropForeign('clark_ibfk_6');
            $table->dropForeign('clark_ibfk_8');
            $table->dropForeign('clark_ibfk_3');
        });
    }
};
