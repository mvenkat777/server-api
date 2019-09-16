<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\EloquentStyleRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;

class GetAllStyleCommandHandler implements CommandHandler 
{

	/**
	 * @param LineRepository $line
	 * @return void
	 */
	public function __construct(StyleRepository $style)
	{
            $this->style = $style;
	}

	/**
	 * Handles GetAllLinesMetaCommand
	 *
	 * @param GetAllLinesMetaCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
            return $this->style->getAllStyle($command);
	}
}
