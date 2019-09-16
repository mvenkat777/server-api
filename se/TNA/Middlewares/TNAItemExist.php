<?php
namespace Platform\TNA\Middlewares;

use App\Http\Controllers\ApiController;
use Closure;
use League\Fractal\Manager;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;

class TNAItemExist
{
	protected $tnaItemRepository;

	function __construct(TNAItemRepository $tnaItemRepository)
	{
		$this->tnaItemRepository = $tnaItemRepository;
	}

	public function handle($request, Closure $next)
    {
        $result = $this->tnaItemRepository->getById($request->tnaItemId);
        if($result){
            $request->tnaItem = $result;
            return $next($request);
        }
        else
        	return (new ApiController(new Manager()))->setStatusCode(422)->respondWithError('Invalid TNA item', 'SE_4200103');
    }
}
