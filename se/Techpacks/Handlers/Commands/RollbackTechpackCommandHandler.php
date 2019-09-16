<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class RollbackTechpackCommandHandler implements CommandHandler 
{

    /**
     * @param TechpackRepository $techpackRepository
     */
	public function __construct(TechpackRepository $techpackRepository)
	{
        $this->techpackRepository = $techpackRepository;
	}

    /**
     * Handles the techpack rollback command
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        return $this->techpackRepository->rollback($command->techpackId);
	}

}
