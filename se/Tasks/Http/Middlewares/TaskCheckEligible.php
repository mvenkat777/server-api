<?php
namespace Platform\Tasks\Http\Middlewares;

use App\Http\Controllers\ApiController;
use Closure;
use League\Fractal\Manager;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class TaskCheckEligible
{
	protected $taskRepository;

    protected $condition;

	function __construct(TaskRepository $taskRepository)
	{
		$this->taskRepository = $taskRepository;
	}

	public function handle($request, Closure $next, $who='owner')
    {
        $task = !is_null($request->task) ? $request->task : $this->taskRepository->getTaskById($request->taskId);
        $result = $this->checkAll($who, $task);
        if($result)
        	return $next($request);
        else
        	return (new ApiController(new Manager()))->setStatusCode(404)->respondWithError('This Task does not belong to you.', 'SE_786105');
    }

    private function checkCondition($task, $who)
    {
        if($who === 'follower'){
            return in_array(\Auth::user()->id, $task->followers->lists('follower_id')->toArray());
        } elseif ($who === 'assignee') {
            return $task->assignee_id == \Auth::user()->id;
        } else {
            return $task->creator_id == \Auth::user()->id;
        }
    }

    private function checkAll($who, $task)
    {
        $orData = explode('|', $who);
        foreach ($orData as $data1) {
            if(strpos($data1, '&')){
                $andData = explode('&', $data1);
                foreach ($andData as $data2) {
                    if(is_null($this->condition)) {
                        $this->condition = true;
                    }
                    $this->condition = $this->condition && $this->checkCondition($task, $data2);
                }
            } else {
                if(is_null($this->condition)) {
                    $this->condition = false;
                }
                $this->condition = $this->condition || $this->checkCondition($task, $data1);
            }
        }
        return $this->condition;
    }
}