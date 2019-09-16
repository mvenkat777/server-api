<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\App\Exceptions\SeException;

class DestroyBoardCommandHandler implements CommandHandler 
{
    /**
     * @param BoardRepository $board
     */
	public function __construct(BoardRepository $board)
	{
        $this->board = $board;
	}

    /**
     * Handles updating a product folder
     *
     * @param DestroyBoardCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }

        return $board->delete();
	}
}
