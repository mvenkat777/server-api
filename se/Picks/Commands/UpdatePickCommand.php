<?php

namespace Platform\Picks\Commands;

class UpdatePickCommand 
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
     * @var string
     */
    public $data;

    /**
     * @param string $boardId
     * @param string $pickId
     */
	public function __construct($boardId, $pickId, $data){
        $this->boardId = $boardId;
        $this->pickId = $pickId;
        $this->data = $data;
	}
}
