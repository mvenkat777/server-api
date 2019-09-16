<?php

namespace Platform\Groups\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Repositories\Contracts\UserTokenRepository;
use Platform\Groups\Repositories\Contracts\GroupRepository;

class CreateGroupCommandHandler implements CommandHandler
{
    protected $groupRepo;
    protected $userRepo;
    protected $tokenRepo;

    public function __construct(
        GroupRepository $groupRepo,
        UserRepository $userRepo,
        UserTokenRepository $tokenRepo
    ) {
        $this->groupRepo = $groupRepo;
        $this->userRepo = $userRepo;
        $this->tokenRepo = $tokenRepo;
    }

    public function handle($command)
    {
        $userId = $this->tokenRepo->getByToken($command->token);
        $userEmail = $this->userRepo->userById($userId->user_id);
        $command = (array) $command;
        unset($command['token']);
        $command['owner_email'] = $userEmail->email;
        $group = $this->groupRepo->createGroup($command);

        return $group;
    }
}
