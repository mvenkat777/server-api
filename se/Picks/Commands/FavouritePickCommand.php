<?php

namespace Platform\Picks\Commands;

class FavouritePickCommand 
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
