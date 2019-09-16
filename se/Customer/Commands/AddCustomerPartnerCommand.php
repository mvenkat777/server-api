<?php

namespace Platform\Customer\Commands;



class AddCustomerPartnerCommand
{
	/**
	 * @var string
	 */
    public $customerId;

    /**
	 * @var array
	 */
	public $partners;
	
	function __construct($data, $customerId)
    {	
        $this->customerId = $customerId;
        $this->partners = is_null($data['partners'])? NULL : $data['partners'];
    } 
}