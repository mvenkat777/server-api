<?php

namespace Platform\Payment\Commands;

class GetAllOrdersCommand{

	/**
	 * @var integer
	 */
    public $items;

	function __construct($items)
    {
    	$this->items = $items;
    }


}