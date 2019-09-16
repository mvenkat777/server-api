<?php

namespace Platform\TNA\Commands;

use Carbon\Carbon;
use Platform\TNA\Helpers\TNAHelper;

class CreateTNACommand 
{
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
	 * @var string DATE
	 */
	public $startDate;
	
	/**
	* @var string DATE
	*/
	public $projectedDate;

	/**
	 * @var string DATE
	 */
	public $targetDate;

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
	 * @var string (100)
	 */
	public $orderQuantity;

	/**
	 * Actually it is styleCode as there is no styleId
	 * @var string
	 */
	public $styleId;

	/**
	 * @var string (Ex:20-40)
	 */
	public $styleRange;

	/**
	 * @var TEXT
	 */
	public $styleDescription;

	/**
	 * @var email
	 */
	public $representor;

	/**
	 * Use to give functionality for if user want empty tna or default tna
	 * @var boolean
	 */
	public $isCreateTemplate;

    /**
     * @var array
     */
    public $departments;

    /**
     * @var string
     */
    public $cloningTnaId;

    /**
     * @var boolean
     */
    public $wantCloning;

    /**
     * @param array $data
     */
	public function __construct($data){
		$this->orderId = TNAHelper::isSetAndIsNotEmpty($data, 'orderId') ? $data['orderId'] : NULL;
		$this->techpackId = TNAHelper::isSetAndIsNotEmpty($data, 'techpackId') ? $data['techpackId'] : NULL;
		$this->customerId = TNAHelper::isSetAndIsNotEmpty($data, 'customerId') ? $data['customerId'] : NULL;
		$this->title = $data['title'];
		$this->startDate = isset($data['startDate']) ? Carbon::parse($data['startDate']) : NULL;
		$this->projectedDate = isset($data['startDate']) ? Carbon::parse($data['startDate']) : NULL;
		$this->targetDate = Carbon::parse($data['targetDate']);
		$this->customerName = isset($data['customerName']) ? $data['customerName'] : NULL;
		$this->customerCode = isset($data['customerCode']) ? $data['customerCode'] : NULL;
		$this->orderQuantity = isset($data['orderQuantity']) ? $data['orderQuantity'] : NULL;
		$this->styleId = isset($data['styleCode']) ? $data['styleCode'] : NULL;
		$this->styleRange = isset($data['styleRange']) ? $data['styleRange'] : NULL;
		$this->styleDescription = isset($data['styleDescription']) ? $data['styleDescription'] : NULL;
		$this->representor = $data['representor'];
		$this->itemsOrder = isset($data['itemsOrder']) ? $data['itemsOrder'] : json_decode(json_encode('[]'));
		$this->isCreateTemplate = isset($data['isCreateTemplate']) && !is_null($data['isCreateTemplate']) ? $data['isCreateTemplate'] : false;
		$this->departments = isset($data['categories']) ? $data['categories']:NULL;
        $this->wantCloning = isset($data['isCloneCalendar']) ? $data['isCloneCalendar'] : false;
        $this->cloningTnaId = isset($data['cloneingTnaId']) ? $data['cloneingTnaId'] : null;
	}

}
