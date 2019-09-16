<?php

namespace Platform\Materials\Handlers\Commands;

use Platform\Materials\Commands\CheckUniqueMaterialCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Materials\Repositories\Contracts\MaterialRepository;


class CheckUniqueMaterialCommandHandler implements CommandHandler
{
    /**
     * @var CustomerRepository
     */
    private $materialRepository;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param MaterialRepository
     */
    public function __construct(MaterialRepository $materialRepository,DefaultCommandBus $commandBus)
    {
        $this->materialRepository = $materialRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  CreateCustomerCommand
     * @return mixed
     */
    public function handle($command)
    {   
      
        $materialRows = $this->materialRepository->checkUniqueMaterial($command);
        
        return $materialRows;
        
        //dd('dsfsdfsd');
    }

    
}

