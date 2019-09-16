<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNATemplateRepository;

class DeleteTNATemplateCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\TNA\Repositories\Contracts\TNATemplateRepository;
     */
    protected $tnaTemplateRepository;

	public function __construct(TNATemplateRepository $tnaTemplateRepository)
	{
        $this->tnaTemplateRepository = $tnaTemplateRepository;
	}

	public function handle($command)
	{
        return $this->tnaTemplateRepository->deleteTemplate($command->templateId);
	}

}
