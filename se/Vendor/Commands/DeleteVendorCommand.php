<?php

namespace Platform\Vendor\Commands;

class DeleteVendorCommand{

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->vendorId = $id;
    }
}