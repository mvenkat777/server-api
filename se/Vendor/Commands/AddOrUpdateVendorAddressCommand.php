<?php

namespace Platform\Vendor\Commands;

class AddOrUpdateVendorAddressCommand
{

    /**
     * @var integer
    */
    public $vendorId;

    /**
     * @var integer
    */
    public $addressId;
    
    function __construct($data, $vendorId)
    {
        $this->vendorId = $vendorId;
		$this->addresses = $data['addresses'];
    }
}