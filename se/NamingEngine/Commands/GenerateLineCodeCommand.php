<?php

namespace Platform\NamingEngine\Commands;

class GenerateLineCodeCommand 
{
    /**
     * @var string
     */
    public $customerCode;

    public function __construct($customerCode){
        $this->customerCode = strtoupper($customerCode);
    }
}
