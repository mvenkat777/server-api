<?php

namespace Platform\Materials\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Materials\Repositories\Contracts\MaterialRepository;

class GetAllMaterialCommandHandler implements CommandHandler
{
	protected $materialRepo;

	function __construct(MaterialRepository $materialRepo)
	{
		$this->materialRepo = $materialRepo;
	}

	public function handle($command)
	{
		//dd($command);
		return $this->materialRepo->getAllMaterials($command);
	}
}