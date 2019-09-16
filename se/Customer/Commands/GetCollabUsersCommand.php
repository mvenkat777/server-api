<?php

namespace Platform\Customer\Commands;

class GetCollabUsersCommand 
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @param string $customerId
     */
	public function __construct($customerId){
        $this->customerId = $customerId;
	}

}
