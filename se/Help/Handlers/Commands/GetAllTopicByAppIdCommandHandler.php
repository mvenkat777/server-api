<?php

namespace Platform\Help\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Help\Repositories\Contracts\HelpRepository;

class GetAllTopicByAppIdCommandHandler implements CommandHandler
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
     * @param  ShowHelpBySlugCommand
     * @return mixed
     */
    public function handle($command)
    {  
        
        return $this->helpRepository->getTopicByAppId($command);
    }
}

