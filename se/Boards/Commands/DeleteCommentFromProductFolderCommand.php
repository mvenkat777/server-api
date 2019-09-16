<?php

namespace Platform\Boards\Commands;

class DeleteCommentFromProductFolderCommand
{
	/**
     * @var string
     */
    public $productFolderId;

    /**
     * @var array
     */
    public $commentId;

    public function __construct($productFolderId, $commentId)
    {
        $this->productFolderId = $productFolderId;
        $this->commentId = $commentId;
	}
}
