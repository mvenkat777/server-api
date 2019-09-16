<?php

namespace Platform\Materials\Handlers\Commands;

use Platform\Materials\Commands\CreateMaterialCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Materials\Repositories\Contracts\MaterialRepository;
/*
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
*/

class CreateMaterialCommandHandler implements CommandHandler
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
      
        $material = $this->materialRepository->createMaterial($command);
        return $material;
        //dd('dsfsdfsd');
    }

    
}

