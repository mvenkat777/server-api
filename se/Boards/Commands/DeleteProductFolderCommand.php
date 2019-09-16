<?php

namespace Platform\Boards\Commands;

class DeleteProductFolderCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @var string
     */
    public $productFolderId;

    /**
     * @param mixed $boardId
     * @param mixed $productFolderId
     */
	public function __construct($boardId, $productFolderId){
        $this->boardId = $boardId;
        $this->productFolderId = $productFolderId;
	}
}
