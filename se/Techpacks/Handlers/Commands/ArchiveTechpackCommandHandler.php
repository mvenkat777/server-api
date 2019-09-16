<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class ArchiveTechpackCommandHandler implements CommandHandler 
{

    /**
     * @param TechpackRepository $techpackRepository
     */
	public function __construct(TechpackRepository $techpackRepository)
	{
        $this->techpackRepository = $techpackRepository;
	}

	public function handle($command)
	{
        return $this->techpackRepository->archive($command->techpackId);
	}

}
