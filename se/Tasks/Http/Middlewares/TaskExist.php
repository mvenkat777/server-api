<?php
namespace Platform\Tasks\Http\Middlewares;

use App\Http\Controllers\ApiController;
use Closure;
use League\Fractal\Manager;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class TaskExist
{
	protected $taskRepository;

	function __construct(TaskRepository $taskRepository)
	{
		$this->taskRepository = $taskRepository;
	}

	public function handle($request, Closure $next)
    {
        $result = $this->taskRepository->getTaskById($request->taskId);
        if($result){
            $request->task = $result;
        	return $next($request);
        }
        else
        	return (new ApiController(new Manager()))->setStatusCode(404)->respondWithError('Task does not exist', 'SE_786100');
    }
}