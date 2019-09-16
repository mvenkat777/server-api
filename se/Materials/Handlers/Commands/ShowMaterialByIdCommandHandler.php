<?php

namespace Platform\Materials\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Materials\Repositories\Contracts\MaterialRepository;

class ShowMaterialByIdCommandHandler implements CommandHandler
{
	protected $materialRepo;

	function __construct(MaterialRepository $materialRepo)
	{
		$this->materialRepo = $materialRepo;
	}

	public function handle($command)
	{
		return $this->materialRepo->showMaterialById($command->id);
	}
}