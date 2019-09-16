<?php

namespace Platform\Customer\Commands;

class AddUsersToCustomerCommand 
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @var array
     */
    public $users;

    /**
     * @param string $customerId
     * @param array $users
     */
	public function __construct($customerId, $users){
        $this->customerId = $customerId;
        $this->users = $users;
	}
}
