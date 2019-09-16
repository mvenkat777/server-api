<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\App\Exceptions\SeException;

class CreateBoardCommandHandler implements CommandHandler 
{
    /**
     * @param BoardRepository $board
     * @param CollabRepository $collab
     */
	public function __construct(BoardRepository $board, CollabRepository $collab)
	{
        $this->board = $board;
        $this->collab = $collab;
	}

    /**
     * Handles the creation of a board
     *
     * @param CreateBoardCommand $command
     */
	public function handle($command)
	{
        $collab = $this->collab->getByUrl($command->collabUrl);
        if (!$collab) {
            throw new SeException("Collab url not found.", 404);
        }

        return $this->createBoard($collab, $command->data);
	}


    /**
     * Creates the board
     *
     * @param Platform\Customer\Models\Collab $collab
     * @param array $data
     */
    public function createBoard($collab, $data)
    {
        $board = $this->board->createBoard($data);
        if ($board) {
            $collab->boards()->sync([$board->id], false);
        }        

        return $this->board->getByIdWithRelations($board->id);
    }
}
