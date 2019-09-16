<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\App\Exceptions\SeException;

class GetAllProductFoldersCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    private $board;

    /**
     * @param BoardRepository $board
     * @param ProductFolderRepository $productFolder
     */
	public function __construct(BoardRepository $board)
	{
        $this->board = $board;
	}

    /**
     * Handles creating a new product folder
     *
     * @param CreateProductFolderCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }

        return $board->productFolders()->orderBy('updated_at', 'DESC')->paginate(10);
	}
}
