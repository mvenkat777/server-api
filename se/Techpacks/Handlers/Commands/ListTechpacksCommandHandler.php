<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\Techpacks\Commands\ListTechpacksCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Illuminate\Auth\Guard;

class ListTechpacksCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    protected $techpack;
    protected $auth;
    protected $user;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack, Guard $auth, UserRepository $user)
    {
        $this->techpack = $techpack;
        $this->auth = $auth;
        $this->user = $user;
    }

    /**
     * @param ListTechpacksCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        return $this->techpack->listTechpacks($command, $this->auth->user());
    }
}
