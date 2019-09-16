<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\CollabBoard\Repositories\Contracts\CollabUserRepository;

class AddSalesRepresentativeToCollabCommandHandler implements CommandHandler 
{
    /**
     * @var CollabUserRepository
     */
    public $collabUser;

    /**
     * @param CollabUserRepository $collabUser
     */
	public function __construct(CollabUserRepository $collabUser)
	{
        $this->collabUser = $collabUser;
	}

	public function handle($command)
	{
         return $this->collabUser->addSalesRepresentative(
            $command->collabId, $command->salesRepresentativeId
        );
	}

}
