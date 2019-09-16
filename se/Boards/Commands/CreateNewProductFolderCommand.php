<?php

namespace Platform\Boards\Commands;

class CreateNewProductFolderCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @var array
     */
    public $data;

    /**
     * @param string $boardId
     * @param array $data
     */
	public function __construct($boardId, $data){
        $this->boardId = $boardId;
        $this->data = $data;
	}
}
