<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('creator_id', 100);
            $table->text('assignee_id')->nullable();
            $table->boolean('is_assignee_group')->default(false);
            $table->timestamp('due_date')->nullable();
            $table->timestamp('seen')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('submission_date')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->integer('priority_id')->unsigned()->default(1);
            $table->string('location')->nullable();
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('assignee_status')->unsigned()->default(2);
            $table->timestamp('snoozed_time')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignee_status')->references('id')->on('task_assignee_status')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('task_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
