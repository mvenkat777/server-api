<?php

namespace Platform\Boards\Commands;

class UpdateBoardCommand 
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
    public function __construct($collabUrl, $boardId, $data)
    {
        $this->boardId = $boardId;
        $this->data = $data;
	}
}
