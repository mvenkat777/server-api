<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;

class GetProductFolderCommentsCommandHandler implements CommandHandler
{
	/**
     * @var ProductFolderRepository
     */
    private $productFolder;

    /**
     * @param ProductFolderRepository $productFolder
     */
	public function __construct(ProductFolderRepository $productFolder)
	{
        $this->productFolder = $productFolder;
	}

	public function handle($command)
	{
        $productFolder = $this->productFolder->find($command->productFolderId);
        if (!$productFolder) {
            throw new SeException("Product Folder not found.", 404);
        }

        return $productFolder->comments()->paginate(20);
	}
}
