<?php

namespace Platform\NamingEngine\Commands;

class GenerateVendorCodeCommand 
{
    /**
     * @var string
     */
    public $vendorName;

    public function __construct($vendorName){
        $this->vendorName = $vendorName;
    }
}
