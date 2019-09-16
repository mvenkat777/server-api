<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCreatingPresetToTna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna', function (Blueprint $table) {
            $table->boolean('is_creating_preset')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna', function (Blueprint $table) {
           $table->dropColumn('is_creating_preset');
        });
    }
}
