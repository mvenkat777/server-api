<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\App\Exceptions\SeException;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;

class CreateNewProductFolderCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    private $board;

    /**
     * @var ProductFolderRepository
     */
    private $productFolder;

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

        return $this->createProductFolder($board, $command->data);
	}
    
    /**
     * Create the product folder and attachs the picks and to the board
     *
     * @param Board $board
     * @param array $data
     */
    private function createProductFolder($board, $data)
    {
        $productFolder = $this->productFolder->createProductFolder($data);

        if ($productFolder) {
            $this->attachPicks($productFolder, $data['pickIds']);
            $board->productFolders()->sync([$productFolder->id], false);
            return $productFolder;
        } else {
            throw new SeException("Failed to create Product Folder");
        }
    }

    /**
     * attaches the picks to product folder
     *
     * @param ProductFolder $productFolder
     * @param mixed $pickIds
     */
    private function attachPicks($productFolder, $pickIds)
    {
        if (is_array($pickIds)) {
            $productFolder->picks()->sync($pickIds, false);
        } else {
            $productFolder->picks()->sync([$pickIds], false);
        }
    }
}
