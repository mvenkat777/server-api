<?php

namespace Platform\Help\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Help\Repositories\Contracts\HelpRepository;



class CreateHelpCommandHandler implements CommandHandler
{
 
    /**
     * @var HelpRepository
     */
    private $helpRepository;

    /**
     * @param HelpRepository
     */
    public function __construct(HelpRepository $helpRepository)
    {
        $this->helpRepository = $helpRepository;
        
    }

    /**
     * @param  CreateHelpCommand
     * @return mixed
     */
    public function handle($command)
    {   
        return $this->helpRepository->makeHelp($command);
    }
}

