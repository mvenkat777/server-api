<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskIdToTnaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_items', function (Blueprint $table) {
            $table->string('task_id')->nullable();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
            // \DB::statemant('ALTER TABLE tna_items DROP COLUMN task_id');
            $table->dropColumn('task_id');
        });
    }
}
