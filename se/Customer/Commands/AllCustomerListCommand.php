<?php

namespace Platform\Customer\Commands;

class AllCustomerListCommand {

	/**
	 * @var int
	 */
	public $item;

	/**
	 * @param array $data 
	 */
    function __construct($data)
    {
       $this->item = isset($data['item'])? $data['item']:100;
    }
}