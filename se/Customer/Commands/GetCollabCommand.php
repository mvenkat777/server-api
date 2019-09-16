<?php

namespace Platform\Customer\Commands;

class GetCollabCommand 
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
