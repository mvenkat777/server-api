<?php

namespace Platform\CollabBoard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Authentication\Providers\GoogleProvider;
use Platform\App\Exceptions\SeException;
use Platform\Authentication\Commands\AuthenticateGoogleUserCommand;

class GoogleInviteAcceptCommandHandler implements CommandHandler 
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

    /**
     * @var DefaultCommandBus
     */
    private $google;

    public function __construct(
        CollabRepository $collab, 
        CollabInviteRepository $collabInvite,
        UserRepository $user,
        DefaultCommandBus $commandBus,
        GoogleProvider $google
    ) {
        $this->collab = $collab;
        $this->collabInvite = $collabInvite;
        $this->user = $user;
        $this->commandBus = $commandBus;
        $this->google = $google;
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
        
        $googleUser = $this->google->getUserByToken($command->googleToken);
	    if (!$googleUser) {
            throw new SeException("Invalid google token.", 404);
        }

        if (strtolower($googleUser['email']) !== strtolower($invitedUser->user->email)) {
            throw new SeException("Emails doesn't match.", 404);
        }

        $this->collabInvite->accept($invitedUser);
        return $this->authenticateUser($command->googleToken);
	}

    /**
     * Authenticate the user
     *
     * @param App\User $user
     * @param LegacyInviteAcceptCommand $command
     */
    public function authenticateUser($googleToken)
    {
        return $this->commandBus->execute(new AuthenticateGoogleUserCommand($googleToken));
    }
}
