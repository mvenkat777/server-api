<?php

namespace Platform\Tasks\Commands;

class GetArchivedTaskByTypeCommand 
{
    const ITEM = 100;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var number
	 */
	public $item;

	public function __construct($data){
		$this->type = isset($data['type']) ? $data['type'] : 'all' ;
		$this->item = isset($data['item']) ? $data['item'] : self::ITEM ;
	}
}
