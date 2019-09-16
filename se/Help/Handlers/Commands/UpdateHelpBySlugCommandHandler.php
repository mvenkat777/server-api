<?php

namespace Platform\Help\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Help\Repositories\Contracts\HelpRepository;

class UpdatehelpBySlugCommandHandler implements CommandHandler
{
   
    private $helpRepository;

    /**
     * @param HelpRepository
     */
    public function __construct(HelpRepository $helpRepository)
    {
        $this->helpRepository = $helpRepository;
    }

    /**
     * @param  UpdateHelpCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->helpRepository->updateHelp($command);
    }

}

