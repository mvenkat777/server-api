<?php

namespace Platform\Address\Commands;

class DestroyUserAddressCommand {

	/**
	 * @var string
	 */
    public $userId;
    /**
	 * @var string
	 */
    public $id;


    function __construct($userId,$id)
    {
        $this->userId = $userId;
        $this->id = $id;
    }


}