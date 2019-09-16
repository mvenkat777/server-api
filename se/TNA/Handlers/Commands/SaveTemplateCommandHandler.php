<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNATemplateRepository;
use Platform\App\Commanding\DefaultCommandBus;

class SaveTemplateCommandHandler implements CommandHandler 
{

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNATemplateReposiotory
	 */
	protected $tnaTemplateRepo;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @param TNATemplateRepository   $tnaTemplateRepo         
     * @param DefaultCommandBus   $commandBus
	 */
	public function __construct(
        TNATemplateRepository $tnaTemplateRepo,
		DefaultCommandBus $commandBus)
	{
		$this->tnaTemplateRepo = $tnaTemplateRepo;
		$this->commandBus = $commandBus;
	}

    public function handle($command)
    {
        return $this->tnaTemplateRepo->saveTemplate((array)$command);
    }

}

