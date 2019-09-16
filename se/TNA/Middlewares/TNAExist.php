<?php
namespace Platform\TNA\Middlewares;

use Closure;
use League\Fractal\Manager;
use Platform\TNA\Repositories\Contracts\TNARepository;
use App\Http\Controllers\ApiController;

class TNAExist
{
	protected $tnaRepository;

	function __construct(TNARepository $tnaRepository)
	{
		$this->tnaRepository = $tnaRepository;
	}

	public function handle($request, Closure $next)
    {
        $result = $this->tnaRepository->getById($request->tid);
        if($result){
            $request->tna = $result;
            return $next($request);
        }
        else
        	return (new ApiController(new Manager()))->setStatusCode(422)->respondWithError('Invalid TNA', 'SE_4200103');
    }
}
