<?php

namespace Platform\Orders\Commands;



class CreateOrderCommand
{
	/**
	 * @var string
	 */
    public $customerId;

	/**
	 * @var string
	 */
	public $value;

	/**
	 * @var string
	 */
	public $quantity;
	
	/**
	 * @var string
	 */
	public $size;
	
	/**
	 * @var string
	 */
	public $expectedDeliveryDate;

	/**
	 * @var array
	 */
	public $vendorId;

	/**
	 * @var array
	 */
	public $techpackId;


	function __construct($data)
    {	
    	$this->customerId = $data['customerId'];
    	$this->vendorId = isset($data['vendorId'])? $data['vendorId']:[];
    	$this->techpackId = isset($data['techpackId'])? $data['techpackId']:[];
    	$this->value = isset($data['value'])? $data['value']:NULL;
    	$this->quantity = isset($data['quantity'])? $data['quantity']:NULL;
    	$this->size = isset($data['size'])? $data['size']:NULL;
    	$this->expectedDeliveryDate = isset($data['expectedDeliveryDate'])? $data['expectedDeliveryDate']:NULL;
    }
}