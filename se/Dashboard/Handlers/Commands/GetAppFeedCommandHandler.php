<?php

namespace Platform\Dashboard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use App\Task;

class GetAppFeedCommandHandler implements CommandHandler 
{

	public function __construct()
	{

	}

	public function handle($command)
	{
        $tasks = Task::where('assignee_id', $command->user->id)
                        ->orWhere('creator_id', $command->user->id)
                        ->paginate($command->items);
        return $tasks;
        dd($tasks);
        $command->taskId = 'e0ff56f7-1016-401e-84da-9a29c2db464e';
        dd((new TaskActivityRepository)->getTaskDetails($command));
        dd($command);
	}

}
