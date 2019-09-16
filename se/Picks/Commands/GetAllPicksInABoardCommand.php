<?php

namespace Platform\Picks\Commands;

class GetAllPicksInABoardCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @param string $boardId
     */
	public function __construct($boardId){
        $this->boardId = $boardId;
	}
}
