<?php

namespace Platform\CollabBoard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Authentication\Commands\AuthenticateUserCommand;

class LegacyInviteAcceptCommandHandler implements CommandHandler 
{
    /**
     * @var CollabRepository
     */
    private $collab;

    /**
     * @var CollabInviteRepository
     */
    private $collabInvite;

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    public function __construct(
        CollabRepository $collab, 
        CollabInviteRepository $collabInvite,
        UserRepository $user,
        DefaultCommandBus $commandBus
    ) {
        $this->collab = $collab;
        $this->collabInvite = $collabInvite;
        $this->user = $user;
        $this->commandBus = $commandBus;
	}

	public function handle($command)
	{
        $collab = $this->collab->getByUrl($command->collabUrl);
        if (!$collab) {
            throw new SeException("Collab with url not found.", 404);
        }

        $invitedUser = $this->collabInvite->getByCollabIdAndInviteCode($collab->id, $command->inviteCode);
	    if (!$invitedUser) {
            throw new SeException("Invite link not valid. Please request for a new invite.", 404);
        }

        $this->collabInvite->accept($invitedUser);
        $user = $this->updateUser($command, $invitedUser);
        return $this->authenticateUser($user, $command);
	}

    /**
     * Update user in users table
     *
     * @param LegacyInviteAcceptCommand $command
     * @param CollabInvite $invitedUser
     */
    public function updateUser($command, $invitedUser)
    {
        $data = [
            'display_name' => $command->data->displayName,
            'password' => bcrypt($command->data->password),
            'is_active' => true,
        ];
        $user = $this->user->updateUser($data, $invitedUser->user->id);
        if ($user) {
            $user->providers()->sync([1], false);
        }  
        return $user;
    }

    /**
     * Authenticate the user
     *
     * @param App\User $user
     * @param LegacyInviteAcceptCommand $command
     */
    public function authenticateUser($user, $command)
    {
        $auth = new \stdClass();
        $auth->email = $user->email;
        $auth->password = $command->data->password;
        $authenticatedUser = $this->commandBus->execute(new AuthenticateUserCommand($auth));
        return $authenticatedUser;
    }
}
