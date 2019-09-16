<?php

namespace Platform\Boards\Commands;

class GetBoardByIdCommand 
{
    /**
     * @var string
     */
    public $collabUrl;

    /**
     * @var string
     */
    public $boardId;

    /**
     * @param string $collabUrl
     * @param string $boardId
     */
	public function __construct($collabUrl, $boardId){
        $this->collabUrl = $collabUrl;
        $this->boardId = $boardId;
	}
}
