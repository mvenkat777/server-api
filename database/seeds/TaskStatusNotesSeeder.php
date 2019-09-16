<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TasStatusNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = \App\Task::whereNotNull('notes')->get();
        foreach ($tasks as $key => $task) {
            if($task->notes !== ''){
                $task->statusNote()->sync([$task->statusId => ['note' => $task->notes]]);
            }
        }

        DB::statement("ALTER TABLE tasks DROP COLUMN notes");
    }
}
