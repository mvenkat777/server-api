<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Boards\Repositories\Contracts\BoardRepository;

class GetAllPicksInABoardCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    private $board;

    /**
     * @param BoardRepository $board
     */
	public function __construct(BoardRepository $board)
	{
        $this->board = $board;
	}

    /**
     * Handles getting all the picks in a board
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }

        return $board->picks()->paginate(100); 
	}
}
