<?php

namespace Platform\Techpacks\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Commands\GetTechpackSchemaCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class GetTechpackSchemaCommandHandler implements CommandHandler
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
     * @param ListTechpacksCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->getTechpackSchema($command);
    }
}
