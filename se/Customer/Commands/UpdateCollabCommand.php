<?php

namespace Platform\Customer\Commands;

class UpdateCollabCommand 
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @var array
     */
    public $data;

    /**
     * @param tringcustomerId
     * @param array $data
     */
	public function __construct($customerId, $data){
        $this->customerId = $customerId;
        $this->data = $data;
	}

}
