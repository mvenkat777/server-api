<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\App\Exceptions\SeException;

class GetPickInBoardByIdCommandHandler implements CommandHandler 
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

        return $board->picks()->find($command->pickId); 
	}
}
