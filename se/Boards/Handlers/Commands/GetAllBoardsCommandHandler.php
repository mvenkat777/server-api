<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;

class GetAllBoardsCommandHandler implements CommandHandler
{
	/**
	 * @param BoardRepository
	 */
	public function __construct(BoardRepository $board)
	{
		$this->board = $board;
	}

	public function handle($command)
	{
		return $this->board->paginate(30);
	}

}
