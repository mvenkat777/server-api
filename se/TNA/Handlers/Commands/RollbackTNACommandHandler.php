<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;

class RollbackTNACommandHandler implements CommandHandler 
{

	public function __construct(TNARepository $tnaRepository)
	{
        $this->tnaRepository = $tnaRepository;
	}

	public function handle($command)
	{
        return $this->tnaRepository->rollback($command->tnaId);
	}

}
