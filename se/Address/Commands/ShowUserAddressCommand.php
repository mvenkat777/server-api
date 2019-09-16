<?php

namespace Platform\Address\Commands;

class ShowUserAddressCommand {

	/**
	 * @var string
	 */
    public $userId;

    function __construct($userId)
    {
        $this->userId = $userId;
    }


}