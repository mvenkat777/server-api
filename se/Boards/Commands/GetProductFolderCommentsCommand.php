<?php

namespace Platform\Boards\Commands;

class GetProductFolderCommentsCommand
{
	/**
     * @var string
     */
    public $productFolderId;

    /**
     * @param string $productFolderId
     */
    public function __construct($productFolderId)
    {
        $this->productFolderId = $productFolderId;
	}
}
