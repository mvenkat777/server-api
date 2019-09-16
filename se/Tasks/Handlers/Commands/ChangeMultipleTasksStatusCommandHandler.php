<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Commands\DeleteTaskCommand;
use Platform\Tasks\Commands\StartTaskCommand;
use Platform\Tasks\Commands\SubmitTaskCommand;
use Platform\Tasks\Commands\CompleteTaskCommand;
use Platform\Tasks\Commands\CloseTaskCommand;
use Platform\Tasks\Commands\ReassignTaskCommand;
use Platform\App\Commanding\DefaultCommandBus;

class ChangeMultipleTasksStatusCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

	/**
	 * @param TaskRepository         $taskRepository        
     * @param DefaultCommandBus      $commandBus
	 */
    public function __construct(
        TaskRepository $taskRepository,
        DefaultCommandBus $commandBus)
	{
		$this->taskRepository = $taskRepository;
        $this->commandBus = $commandBus;
	}

	public function handle($command)
	{
        foreach($command->taskIds as $taskId) {
            $task = $this->taskRepository->getTaskById($taskId);

            if(!is_null($task)) {
                try {
                    switch($command->action) {
                        case 'delete':
                            $this->commandBus->execute(new DeleteTaskCommand($task->id));
                            break;
                        case 'start':
                            $this->commandBus->execute(new StartTaskCommand(['seeTask' => true], $task->id));
                            break;
                        case 'submit':
                            $this->commandBus->execute(new SubmitTaskCommand([], $task->id));
                            break;
                        case 'complete':
                            $this->commandBus->execute(new CompleteTaskCommand(['note' => ""], $task->id));
                            break;
                        case 'close':
                            $this->commandBus->execute(new CloseTaskCommand(['note' => ""], $task->id));
                            break;
                        case 'reassign':
                            if(!is_null($command->email)) {
                                $this->commandBus->execute(new ReassignTaskCommand($task->id, ['assignee' => $command->email]));
                            }
                            break;
                        default:
                            throw new SeException('Wrong action choosen', 422, 786119);
                    }
                } catch(\Exception $e) {
                }
            }
        }
        return true;
	}

}
