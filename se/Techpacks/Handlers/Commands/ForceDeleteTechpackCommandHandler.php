<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\Techpacks\Commands\ForceDeleteTechpackCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class ForceDeleteTechpackCommandHandler implements CommandHandler
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
     * @param ForceDeleteTechpackCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->forceDeleteTechpack($command);
    }
}
