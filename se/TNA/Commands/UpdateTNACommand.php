<?php

namespace Platform\TNA\Commands;

use Carbon\Carbon;
use Platform\TNA\Helpers\TNAHelper;

class UpdateTNACommand 
{
	/**
	 * @var string UUID
	 */
	public $tnaId;

	/**
	 * @var string UUID
	 */
	public $orderId;

	/**
	 * @var string UUID
	 */
	public $techpackId;

	/**
	 * @var string UUID
	 */
	public $customerId;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var integer
	 */
	public $type;

	/**
	 * @var string DATE
	 */
	public $targetDate;

	/**
	 * @var string DATE
	 */
	public $startDate;

	/**
	 * @var json
	 */
	public $itemsOrder;

	/**
	 * @var string
	 */
	public $customerName;

	/**
	 * @var string
	 */
	public $customerCode;

	/**
	 * @var number
	 */
	public $orderQuantity;

	/**
	 * @var string
	 */
	public $styleId;

	/**
	 * @var string
	 */
	public $styleRange;

	/**
	 * @var string
	 */
	public $styleDescription;

	/**
	 * @var string UUID
	 */
	public $representorId;

	public function __construct($data, $id)
	{
		$this->tnaId = $id;
		$this->orderId = TNAHelper::isSetAndIsNotEmpty($data, 'order') 
							? $data['order']['id']
							: null;
		$this->techpackId = TNAHelper::isSetAndIsNotEmpty($data, 'techpack') 
							? $data['techpack']['id']
							: null;
		//$this->vendors = array_column($data['vendors'], 'vendorId');
		$this->customerId = $data['customerId'];
		$this->title = $data['title'];
		$this->startDate = Carbon::parse($data['startDate']);
		$this->targetDate = Carbon::parse($data['targetDate']);
		$this->customerName = $data['customerName'];
		$this->customerCode = $data['customerCode'];
		$this->orderQuantity = TNAHelper::isSetAndIsNotEmpty($data, 'order') 
									? $data['order']['quantity']
									: null;
		$this->styleId = TNAHelper::isSetAndIsNotEmpty($data, 'techpack')
							? $data['techpack']['styleCode']
							: null;
		$this->styleRange = TNAHelper::isSetAndIsNotEmpty($data, 'order') 
							? $data['order']['sizeRange']
							: null;
		$this->styleDescription = TNAHelper::isSetAndIsNotEmpty($data, 'styleDescription') 
							? $data['styleDescription']
							: null;
		$this->representorId = $data['representor']['id'];
    	$this->itemsOrder = isset($data['itemsOrder']) ? $data['itemsOrder'] : json_decode(json_encode('['));
	}
}
