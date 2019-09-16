<?php

namespace Platform\Boards\Commands;

class CreateBoardCommand 
{
    /**
     * @var string
     */
    public $collabUrl;

    /**
     * @var array
     */
    public $data;

    /**
     * @param string $collabUrl
     * @param array $data
     */
    public function __construct($collabUrl, $data)
    {
        $this->collabUrl = $collabUrl;
        $this->data = $data;
	}
}
