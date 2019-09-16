<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTnaItemPresetColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_item_presets', function (Blueprint $table) {
            $table->dateTime('planned_date')->nullable();
            $table->renameColumn('is_current', 'is_parallel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_item_presets', function (Blueprint $table) {
            $table->renameColumn('is_parallel', 'is_current');
            $table->dropColumn('planned_date');
        });
    }
}
