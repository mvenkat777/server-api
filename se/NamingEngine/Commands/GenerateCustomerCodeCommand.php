<?php

namespace Platform\NamingEngine\Commands;

class GenerateCustomerCodeCommand 
{
    /**
     * @var string
     */
    public $customerName;

    public function __construct($customerName){
        $this->customerName = $customerName;
    }
}
