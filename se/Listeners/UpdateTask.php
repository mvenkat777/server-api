<?php
namespace Platform\Listeners;

use Platform\App\Events\EventListener;
use Platform\Tasks\Events\UpdateLog;

use Platform\Tasks\Repositories\Contracts\TaskRepository;

class UpdateTask extends EventListener{

    protected $taskRepository;

    function __construct (TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    public function whenUpdateLog(UpdateLog $event)
    {
        // dd($event);
        return $this->taskRepository->updateLog($event->taskId, $event->message);
    }

}
