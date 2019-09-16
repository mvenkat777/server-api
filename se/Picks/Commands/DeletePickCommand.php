<?php

namespace Platform\Picks\Commands;

class DeletePickCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @var string
     */
    public $pickId;

    /**
     * @param string $boardId
     * @param string $pickId
     */
	public function __construct($boardId, $pickId){
        $this->boardId = $boardId;
        $this->pickId = $pickId;
	}
}
