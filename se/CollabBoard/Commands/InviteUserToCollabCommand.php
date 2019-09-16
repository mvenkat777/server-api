<?php

namespace Platform\CollabBoard\Commands;

class InviteUserToCollabCommand 
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @var array
     */
    public $user;

    /**
     * @param tringcustomerId
     * @param array $user
     */
	public function __construct($customerId, $user){
        $this->customerId = $customerId;
        $this->user = $user;
	}
}
