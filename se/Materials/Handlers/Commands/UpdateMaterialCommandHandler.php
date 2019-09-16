<?php

namespace Platform\Materials\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Materials\Repositories\Contracts\MaterialRepository;

class UpdateMaterialCommandHandler implements CommandHandler
{
	protected $materialRepo;

	function __construct(MaterialRepository $materialRepo)
	{
		$this->materialRepo = $materialRepo;
	}

	public function handle($command)
	{

		$response = $this->materialRepo->UpdateMaterial($command);
		//dd($response);
		if($response){
			return $this->materialRepo->showMaterialById($command->id);
		}else{
			return 'Updated failed';	
		}
		
	}
}