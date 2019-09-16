<?php

namespace Platform\Techpacks\Handlers\Commands;

use App\LibraryItem;
use Illuminate\Auth\Guard;
use App\LibraryItemAttribute;
use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Commands\GetTechpackSchemaCommand;
use Platform\Techpacks\Commands\GenerateTechpackSchemaCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class GenerateTechpackSchemaCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    protected $techpack;
    protected $libraryItem;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack, LibraryItem $libraryItem, Guard $auth)
    {
        $this->techpack = $techpack;
        $this->libraryItem = $libraryItem;
        $this->auth = $auth;
    }


    /**
     * @param ListTechpacksCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->generateTechpackSchema($command, $this->libraryItem, $this->auth->user()->id);
    }
}
