<?php

namespace Platform\Customer\Commands;



class AddCustomerBrandCommand
{
	/**
	 * @var string
	 */
    public $customerId;

    /**
	 * @var array
	 */
	public $brands;
	
	function __construct($data, $customerId)
    {	
        $this->customerId = $customerId;
        $this->brands = is_null($data['brands'])? NULL : $data['brands'];
    } 
}