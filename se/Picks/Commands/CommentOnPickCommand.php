<?php

namespace Platform\Picks\Commands;

class CommentOnPickCommand 
{
    /**
     * @var string
     */
    public $pickId;

    /**
     * @var array
     */
    public $data;

    public function __construct($pickId, $data)
    {
        $this->pickId = $pickId;
        $this->data = $data;
	}

}
