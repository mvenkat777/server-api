<?php

namespace Platform\Apps\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Apps\Repositories\Contracts\AppRepository;

class UpdateAppCommandHandler implements CommandHandler
{
	protected $appRepo;

	function __construct(AppRepository $appRepo)
	{
		$this->appRepo = $appRepo;
	}

	public function handle($command)
	{
		$result=$this->appRepo->UpdateApp((array)$command);
		
		return 'Updated Successfully';
	}
}