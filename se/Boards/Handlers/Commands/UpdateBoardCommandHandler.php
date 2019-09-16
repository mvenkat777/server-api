<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;

class UpdateBoardCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    public $board;

    /**
     * @param BoardRepository $board
     */
	public function __construct(BoardRepository $board)
	{
        $this->board = $board;
	}

    /**
     * Handling updating boards process
     *
     * @param UpdateBoardCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }
        $board->name = $command->data['name'];
        $board->update();

        return $board;
	}

}
