<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskRejectionCycleListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_rejection_log', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('task_id');
            $table->string('creator_id', 100);
            $table->string('assignee_id',100);
            $table->text('reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('tasks_rejection_log');
    }
}
