<?php

namespace Platform\Picks\Commands;

class UploadNewPickCommand 
{
    /**
     * @var string
     */
    public $boardId;

    /**
     * @var array
     */
    public $data;

    public function __construct($boardId, $data) 
    {
        $this->boardId = $boardId;
        $this->data = $data;
	}

}
