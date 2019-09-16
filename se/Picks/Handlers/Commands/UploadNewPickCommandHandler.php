<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Picks\Repositories\Contracts\PickRepository;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\App\Exceptions\SeException;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;

class UploadNewPickCommandHandler implements CommandHandler 
{
    /**
     * @var BoardRepository
     */
    public $board;

    /**
     * @var PickRepository
     */
    public $pick;

    /**
     * @var ProductFolderRepository
     */
    public $productFolder;

    /**
     * @param BoardRepository $board
     * @param PickRepository $pick
     * @param ProductFolderRepository $productFolder
     */
	public function __construct(BoardRepository $board, PickRepository $pick, ProductFolderRepository $productFolder)
	{
        $this->board = $board;
        $this->pick = $pick;
        $this->productFolder = $productFolder;
	}

    /**
     * Handles the uploading of a new pick
     *
     * @param UploadNewPickCommand $command
     */
	public function handle($command)
	{
        $board = $this->board->find($command->boardId); 
        if (!$board) {
            throw new SeException("Could not find the board", 404);
        }

        return $this->createPick($command->boardId, $command->data);
	}

    /**
     * Creates and links the pick
     *
     * @param mixed $boardId
     * @param mixed $data
     */
    public function createPick($boardId, $data)
    {
        $pick = $this->pick->createPick($data);

        if ($pick) {
            $pick->boards()->sync([$boardId], false);
            if (isset(
                $data['productFolderId']) && 
                $this->productFolder->isValidUUID($data['productFolderId']) &&
                !is_null($this->productFolder->find($data['productFolderId']))
            ) {
                $pick->productFolders()->sync([$data['productFolderId']], false);
            }

            if (isset($data['isFavourited']) && $data['isFavourited'] === true) {
                $pick->favouritedUsers()->sync([\Auth::user()->id], false);
            }
        }
        
        return $this->pick->getByIdWithRelations($pick->id);
    }
}
