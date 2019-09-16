<?php

namespace Platform\Tasks\Commands;

class FilterTaskCommand
{
	/**
	 * @var string
	 */
	public $categories;

	/**
	 * @var string
	 */
	public $tags;

	/**
	 * @var string
	 */
	public $date;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var number
	 */
	public $item;

	/**
	 * [It means due Date is expired]
	 * @var boolean
	 */
	public $pending;
	
	/**
	 * @var integer
	 */
	public $priorityId;
	
	/**
	 * @var array
	 */
	function __construct($data)
	{
		$this->categories = isset($data['category']) ? json_decode($data['category']) : NULL;
		$this->tags = isset($data['tags']) ? json_decode($data['tags']) : NULL;
		$this->item = isset($data['item']) ? json_decode($data['item']) : 100;
		$this->date = isset($data['date']) ? json_decode($data['date']) : NULL;
		$this->type = isset($data['type']) ? $data['type'] : 'both';
		$this->pending = (isset($data['pending']) && (boolean)$data['pending']) ? (boolean)$data['pending'] : false;
        $this->priorityId = (isset($data['priority']) && (integer)$data['priority']) 
                                            ? (integer)$data['priority'] 
                                            : null;
	}
}
