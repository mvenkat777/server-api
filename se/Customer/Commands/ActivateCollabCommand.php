<?php

namespace Platform\Customer\Commands;

class ActivateCollabCommand 
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $customerId;

	public function __construct($data, $customerId){
        $this->data = $data;
        $this->customerId = $customerId;
	}
}
