<?php

namespace Platform\Picks\Commands;

class GetPickCommentsCommand 
{
    /**
     * @var string
     */
    public $pickId;

    /**
     * @param string $pickId
     */
    public function __construct($pickId)
    {
        $this->pickId = $pickId;
	}
}
