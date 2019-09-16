<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\Techpacks\Commands\RestoreTechpackCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class RestoreTechpackCommandHandler implements CommandHandler
{
    protected $techpack;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack)
    {
        $this->techpack = $techpack;
    }


    /**
     * @param RestoreTechpackCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->restoreTechpack($command);
    }
}
