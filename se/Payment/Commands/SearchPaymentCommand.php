<?php
namespace Platform\Payment\Commands;

class SearchPaymentCommand {

	/*
	* string $name;
	*/
	public $name; 

	/*
	* string $data;
	*/
	public $data; 

	/**
	 * @var integer
	 */
    public $items;

	/**
     * @param array $data
     */
    function __construct($request)
    {
    	$this->name = $request['name'];
    	$this->data = $request['data'];
    	$this->items = $request['items'];
    }
}