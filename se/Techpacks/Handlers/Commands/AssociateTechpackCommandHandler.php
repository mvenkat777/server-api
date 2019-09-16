<?php

namespace Techpacks\Handlers\Commands;

use Platform\Techpacks\Commands\AssociateTechpackCommand;
use Platform\Techpacks\Commands\GetTechpackByIdCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class AssociateTechpackCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    protected $techpack;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack)
    {
        $this->techpack = $techpack;
    }


    /**
     * @param GetTechpackByIdCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->associateTechpack($command);
    }
}
