<?php

namespace Platform\Techpacks\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Commands\GetTechpackByIdCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackUserRepository;

class GetTechpackByIdCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    protected $techpack;
    private $techpackUserRepository;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack, TechpackUserRepository $techpackUserRepository)
    {
        $this->techpack = $techpack;
        $this->techpackUserRepository = $techpackUserRepository;
    }


    /**
     * @param GetTechpackByIdCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        $techpack = $this->techpack->getTechpackById($command);

        if ($techpack) {
            $isOwner = ($this->techpack->getUserByTechpackId($command->id) == Auth::user()->id);

            $isShared = $this->techpackUserRepository->findByTechpackAndUser($command->id, Auth::user()->id);
            $isPublic = $this->techpack->isPublic($command->id);

            if ($isOwner || $isShared || $isPublic) {
                return $this->techpack->getTechpackById($command);
            } else {
                throw new \Exception('You don\'t have permission to do that.', "0102403");
            }
        } else {
            throw new \Exception('Techpack not found..', "0102403");
        }
    }
}
