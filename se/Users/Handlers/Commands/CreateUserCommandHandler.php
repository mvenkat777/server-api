<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Repositories\Contracts\UserDetailRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\UserWasCreated;
use Platform\Users\Validators\UserValidator;

class CreateUserCommandHandler implements CommandHandler
{
    use EventGenerator;

    /**
     * For checking if sourceeasy mail id or not.
     */
    const domain = '@sourceeasy.com';

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserDetailRepository
     */
    private $UserDetailRepository;

    /**
     * @param EventDispatcher      $dispatcher
     * @param UserRepository       $userRepository
     * @param UserDetailRepository $UserDetailRepository
     */
    public function __construct(
        EventDispatcher $dispatcher,
        UserRepository $userRepository,
        UserDetailRepository $UserDetailRepository
    ) {
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
        $this->UserDetailRepository = $UserDetailRepository;
    }

    /**
     * Handles CreateUserCommand.
     *
     * @param CreateUserCommand $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->userRepository->getByEmail($command->email);
        if ($user) {
            if (UserValidator::isSocialUser($user) && !UserValidator::isLegacyUser($user)) {
                $this->mergeAccount($user->id, $command);
            } else {
                throw new SeException('Email already exists', 409, 3210113);
            }
        }

        if (Helpers::isOriginPlatform() && !$command->admin) {
            if (!Helpers::isSeEmail($command->email)) {
                throw new SeException('You are not allowed here.', 401, 3210108);
            }
        }

        $command->se = Helpers::isSeEmail($command->email);
        $command->isActive = ($command->admin == true || $command->isSocial);

        $newUser = $this->userRepository->createUser($command);

        $userDetail = $this->UserDetailRepository->createUserDetail(['user_id' => $newUser->id]);

        if ($command->admin == true) {
            return $newUser;
        }
        //Generate Event for email
        if ($command->provider == 1 && !$command->sharedTechpack) {
            $newUser->providers()->sync([1], false);
            $this->raise(new UserWasCreated($command, $newUser->confirmation_code));
            $this->dispatcher->dispatch($this->releaseEvents());
        }

        return $newUser;
    }

    /**
     * Merge already existing account with new info
     * @param  object $user
     */
    private function mergeAccount($userId, $data)
    {
        $updatedFields = [
            'display_name' => $data->displayName,
            'password' => bcrypt($data->password),
        ];

        $updatedUser = $this->userRepository->updateUser($updatedFields, $userId);

        if ($updatedUser) {
            $updatedUser->providers()->sync([1], false);
            throw new SeException('We have merged your account.', 200);
        } else {
            throw new SeException('Database Problem', 500, 50001);
        }
    }
}
