<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\LineRepository;

class GetAllLinesCommandHandler implements CommandHandler 
{

	/**
	 * @param LineRepository $line
	 * @return void
	 */
	public function __construct(LineRepository $line)
	{
            $this->line = $line;
	}

	/**
	 * Handles GetAllLinesMetaCommand
	 *
	 * @param GetAllLinesMetaCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
            return $this->line->all();
	}
}
