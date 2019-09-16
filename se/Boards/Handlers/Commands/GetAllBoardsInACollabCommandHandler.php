<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\App\Exceptions\SeException;

class GetAllBoardsInACollabCommandHandler implements CommandHandler
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

    /**
     * Handles getting all the boards ins a collab
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $collab = $this->collab->getByUrl($command->collabUrl);
        if (!$collab) {
            throw new SeException("Collab not found.", 404);
        }

        return $collab->boards()->paginate(20);
	}

}
