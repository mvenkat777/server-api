<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;

class UpdateLastLoginLocationCommandHandler implements CommandHandler
{
    /**
     * @var UserTokenRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $Repository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handles UpdateLastLoginLocationCommand.
     *
     * @param UpdateLastLoginLocationCommand $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        return $this->userRepository->updateLoginLocation($command);
    }
}
