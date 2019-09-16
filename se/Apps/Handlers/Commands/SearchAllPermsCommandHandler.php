<?php

namespace Platform\Apps\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Apps\Repositories\Contracts\AppRepository;

class SearchAllPermsCommandHandler implements CommandHandler
{
	protected $appRepo;

	function __construct(AppRepository $appRepo)
	{
		$this->appRepo = $appRepo;
	}

	public function handle($command)
	{
		return $this->appRepo->allPermissions();
	}
}