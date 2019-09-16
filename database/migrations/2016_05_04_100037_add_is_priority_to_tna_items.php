<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPriorityToTnaItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->boolean('is_priority_task')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->dropColumn(['is_priority_task']);
        });
    }
}
