<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\LineRepository;

class GetAllLinesMetaCommandHandler implements CommandHandler 
{
	/**
	 * @param LineRepository $line
	 * @return void
	 */
	public function __construct(LineRepository $lineRepo)
	{
		$this->lineRepo = $lineRepo;
	}

	/**
	 * Handles GetAllLinesMetaCommand
	 *
	 * @param GetAllLinesMetaCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		return $this->lineRepo->getAllLine($command);
	}

}
