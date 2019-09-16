<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;

class GetCollabCommandHandler implements CommandHandler 
{
    /**
     * @var CollabRepository
     */
    private $collab;

    /**
     * @param CollabRepository $collab
     */
	public function __construct(CollabRepository $collab)
	{
        $this->collab = $collab;
	}

	public function handle($command)
	{
        return $this->collab->getByCustomerIdWithRelations($command->customerId);
	}

}
