<?php

namespace Platform\Techpacks\Handlers\Commands;

use app\TechpackUser;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackUserRepository;

class SearchTechpackCommandHandler implements CommandHandler
{
	protected $techpackUserRepository;
	protected $techpackRepository;

	function __construct(TechpackRepository $techpackRepository , TechpackUserRepository $techpackUserRepository)
	{
		$this->techpackRepository = $techpackRepository;
		$this->techpackUserRepository = $techpackUserRepository;
	}

	public function handle($command)
	{
		if($command->search == 'share')
		{
			return $this->techpackUserRepository->searchShare($command);
		}
		elseif($command->search == 'public')
		{
			return $this->techpackRepository->searchPublic($command);
		}
	}
}
