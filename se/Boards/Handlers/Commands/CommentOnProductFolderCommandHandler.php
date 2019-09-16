<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\ProductFolderCommentRepository;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;

class CommentOnProductFolderCommandHandler implements CommandHandler
{
	/**
     * @var PickRepository
     */
    private $productFolder;

    /**
     * @var ProductFolderCommnetRepository
     */
    private $comment;

    /**
     * @param ProductFolderRepository $productFolder
     * @param PickCommentRepository $comment
     */
	public function __construct(
		ProductFolderRepository $productFolder,
		ProductFolderCommentRepository $comment
	) {
        $this->productFolder = $productFolder;
        $this->comment = $comment;
	}

	/**
	 * Handles adding a comment to a product folder
	 *
	 * @param  CommentOnProductFolderCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
        $productFolder = $this->productFolder->find($command->productFolderId);
        if (!$productFolder) {
            throw new SeException("Product Folder not found.", 404);
        }

        return $this->comment->addComment($command->productFolderId, $command->data);
	}
}
