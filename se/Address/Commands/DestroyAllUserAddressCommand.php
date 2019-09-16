<?php

namespace Platform\Address\Commands;

class DestroyAllUserAddressCommand {

	/**
	 * @var string
	 */
    public $userId;

    function __construct($userId)
    {
        $this->userId = $userId;
    }


}