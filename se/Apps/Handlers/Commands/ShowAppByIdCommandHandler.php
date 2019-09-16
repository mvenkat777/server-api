<?php

namespace Platform\Apps\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Apps\Repositories\Contracts\AppRepository;

class ShowAppByIdCommandHandler implements CommandHandler
{
	protected $appRepo;

	function __construct(AppRepository $appRepo)
	{
		$this->appRepo = $appRepo;
	}

	public function handle($command)
	{
		$result = $this->appRepo->getByIdApp($command->id);
		//dd(is_null($result));
		if(!is_null($result))
		{
			return $result;
		}
		else
		{
			return 'No Record Found';
		}
	}
}