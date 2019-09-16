<?php

namespace Platform\Boards\Commands;

class GetAllProductFoldersCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @param string $boardId
     */
    public function __construct($boardId)
    {
        $this->boardId = $boardId;
	}
}
