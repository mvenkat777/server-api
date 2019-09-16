<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Groups\Repositories\Eloquent\EloquentGroupUserRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Groups\Repositories\Eloquent\EloquentGroupRepository;


class AddUserToGroupCommandHandler implements CommandHandler
{
   

    /**
     * @var GroupRepository
     */
    protected $GroupRepository;

    /**
     * @var GroupUserRepository
     */
    protected $GroupUserRepository;

    /**
     * @var UserRepository
     */
    private $UserRepository;

    /**
     * @param GroupRepository
     * @param GroupUserRepository
     * @param UserRepository
     */
    public function __construct(EloquentGroupRepository $GroupRepository, 
                                UserRepository $UserRepository,
                                EloquentGroupUserRepository $GroupUserRepository)
    {
        $this->GroupRepository = $GroupRepository;
        $this->UserRepository = $UserRepository;
        $this->GroupUserRepository = $GroupUserRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->UserRepository->getByEmail($command->email);
        $group = $this->GroupRepository->getByGroupName($command->groupName);

        $data = ['groupId' => $group->id , 'userId' => $user->id , 'permission' => $command->permission];
        $groupUser = $this->GroupUserRepository->createGroupUser($data);


        return 'Success';
    }
}
