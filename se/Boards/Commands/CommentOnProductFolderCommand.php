<?php

namespace Platform\Boards\Commands;

class CommentOnProductFolderCommand
{
	/**
     * @var string
     */
    public $productFolderId;

    /**
     * @var array
     */
    public $data;

    public function __construct($productFolderId, $data)
    {
        $this->productFolderId = $productFolderId;
        $this->data = $data;
	}
}
