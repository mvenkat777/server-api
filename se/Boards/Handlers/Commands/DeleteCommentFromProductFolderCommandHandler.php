<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\ProductFolderCommentRepository;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;

class DeleteCommentFromProductFolderCommandHandler implements CommandHandler
{
	/**
     * @var ProductFolderRepository
     */
    private $productFolder;

    /**
     * @var ProductFolderCommentRepository
     */
    private $comment;

    /**
     * @param ProductFolderRepository $productFolder
     * @param ProductFolderCommentRepository $comment
     */
	public function __construct(
		ProductFolderRepository $productFolder,
		ProductFolderCommentRepository $comment
	) {
        $this->productFolder = $productFolder;
        $this->comment = $comment;
	}

	public function handle($command)
	{
        $productFolder = $this->productFolder->find($command->productFolderId);
        if (!$productFolder) {
            throw new SeException("Product Folder not found.", 404);
        }
        $comment = $this->comment->find($command->commentId);
        if (!$comment) {
            throw new SeException("Comment not found.", 404);
        }

        return $this->comment->delete($command->commentId);
	}
}
