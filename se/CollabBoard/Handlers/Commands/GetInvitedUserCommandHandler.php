<?php

namespace Platform\CollabBoard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository;
use Platform\App\Exceptions\SeException;

class GetInvitedUserCommandHandler implements CommandHandler 
{
    /**
     * @var CollabRepository
     */
    private $collabRepository;

    /**
     * @var CollabInviteRepository
     */
    private $collabInviteRepository;

    /**
     * @param CollabRepository $collabRepository
     */
    public function __construct(
        CollabRepository $collabRepository,
        CollabInviteRepository $collabInviteRepository
    ) {
        $this->collabRepository = $collabRepository;
        $this->collabInviteRepository = $collabInviteRepository;
	}

    /**
     * Handle getting details of invited user to collab
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $collab = $this->collabRepository->getByUrl($command->collabUrl);
        if (!$collab) {
            throw new SeException("Collab with url not found.", 404);
        }

        $invitedUser = $this->collabInviteRepository->getByCollabIdAndInviteCode($collab->id, $command->inviteCode);
	    if (!$invitedUser) {
            throw new SeException("Invite link not valid. Please request for a new invite.", 404);
        }
        return $invitedUser->user;
    }
}
