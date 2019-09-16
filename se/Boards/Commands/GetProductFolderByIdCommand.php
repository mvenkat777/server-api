<?php

namespace Platform\Boards\Commands;

class GetProductFolderByIdCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @var string
     */
    public $productFolderId;

    public function __construct($boardId, $productFolderId)
    {
        $this->boardId = $boardId;
        $this->productFolderId = $productFolderId;
	}
}
