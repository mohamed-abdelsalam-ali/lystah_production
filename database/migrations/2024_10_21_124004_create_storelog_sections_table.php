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
        Schema::create('storelog_sections', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('store_log_id')->nullable()->index('store_log_id');
            $table->integer('section_id')->nullable()->index('section_id');
            $table->integer('amount')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->integer('store_id')->index('storelog_sections_ibfk_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storelog_sections');
    }
};
