<?php
namespace Platform\Observers\Handlers\Commands;

use Platform\Observers\Repositories\TaskActivityRepository;

class GetTaskActivityDetailsCommandHandler{

	 /**
     * @var taskActivityRepository
     */
    private $taskActivityRepository;

    public function __construct(TaskActivityRepository $taskActivityRepository)
    {
        $this->taskActivityRepository = $taskActivityRepository;
    }
    
    /**
     * @param  StoreNewRuleRequestCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data =$this->taskActivityRepository->getTaskDetails($command);
        return $data;
    }
}