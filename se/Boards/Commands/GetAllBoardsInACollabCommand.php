<?php

namespace Platform\Boards\Commands;

class GetAllBoardsInACollabCommand
{
    /**
     * @var string
     */
    public $collabUrl;

    /**
     * @param mixed $collabUrl
     */
	public function __construct($collabUrl){
        $this->collabUrl = $collabUrl;
	}
}
