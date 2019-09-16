<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;
use Platform\App\Exceptions\SeException;

class UpdateProductFolderCommandHandler implements CommandHandler 
{
    /**
     * @param BoardRepository $board
     * @param ProductFolderRepository $productFolder
     */
	public function __construct(BoardRepository $board, ProductFolderRepository $productFolder)
	{
        $this->board = $board;
        $this->productFolder = $productFolder;
	}

    /**
     * Handles updating a product folder
     *
     * @param UpdateProductFolderCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId);
        if (!$board) {
            throw new SeException("Board not found.", 404);
        }

        $productFolder = $this->productFolder->find($command->productFolderId);
        if (!$productFolder) {
            throw new SeException("Product Folder not found.", 404);
        }

        $productFolder->name = $command->data['name'];
        $productFolder->cover = json_encode($command->data['cover']);
        $productFolder->update();
        return $productFolder;
	}

}
