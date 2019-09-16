<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;

class ArchiveTNACommandHandler implements CommandHandler 
{
    /**
     * @var Platform\TNA\Repositories\Contracts\TNARepository
     */
    protected $tnaRepository;

    /**
     * @param Platform\TNA\Repositories\Contracts\TNARepository $tnaRepository
     */
	public function __construct(TNARepository $tnaRepository)
	{
        $this->tnaRepository = $tnaRepository;
	}

	public function handle($command)
	{
        return $this->tnaRepository->archiveTNA($command->tnaId);
	}

}
