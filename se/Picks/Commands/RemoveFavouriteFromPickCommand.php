<?php

namespace Platform\Picks\Commands;

class RemoveFavouriteFromPickCommand 
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
