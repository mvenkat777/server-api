<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class TaskAssigneeStatusMigrator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = \App\Task::withTrashed()->get();
        foreach ($tasks as $key => $task) {
            $task->assigneeStatus = 1;
            $task->save();
        }
    	Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('assigneeStatus')->references('id')->on('taskAssigneeStatus')->onDelete('cascade');
        });
    }
}
