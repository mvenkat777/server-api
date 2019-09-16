<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\LineRepository;

class GetLineByIdCommandHandler implements CommandHandler 
{
	/**
	 * @param LineRepository $line
	 */
	public function __construct(LineRepository $line)
	{
		$this->line = $line;
	}

	/**
	 * Handles GetLineByIdCommand
	 *
	 * @param GetLineByIdCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
            $lineId = $command->lineId;
            return $this->line->getById($lineId);
	}
}
