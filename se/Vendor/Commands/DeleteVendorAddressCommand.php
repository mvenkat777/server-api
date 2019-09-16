<?php

namespace Platform\Vendor\Commands;

class DeleteVendorAddressCommand
{

    /**
     * @var integer
    */
    public $vendorId;

    /**
     * @var integer
    */
    public $addressId;
    
    function __construct($vendorId, $addressId)
    {
        $this->vendorId = $vendorId;
		$this->addressId = $addressId;
    }


}