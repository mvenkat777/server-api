<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\Picks\Repositories\Contracts\PickRepository;
use Platform\App\Exceptions\SeException;

class UpdatePickCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    private $board;

    /**
     * @var PickRepository
     */
    private $pick;
    /**
     * @param BoardRepository $board
     * @param PickRepository $pick
     */
	public function __construct(BoardRepository $board, PickRepository $pick)
	{
        $this->board = $board;
        $this->pick = $pick;
	}

    /**
     * Handles updating a pick
     *
     * @param UpdatePickCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }

        $pick = $this->pick->find($command->pickId);
        if (!$pick) {
            throw new SeException("Pick not found.", 404);
        }

        $pick->name = $command->data['name'];
        $pick->update();
        return $pick; 
	}

}
