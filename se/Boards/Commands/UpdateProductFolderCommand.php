<?php

namespace Platform\Boards\Commands;

class UpdateProductFolderCommand 
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
     * @var array
     */
    public $data;

	public function __construct($boardId, $productFolderId, $data){
        $this->boardId = $boardId;
        $this->productFolderId = $productFolderId;
        $this->data = $data;
	}
}
