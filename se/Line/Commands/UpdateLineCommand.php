<?php

namespace Platform\Line\Commands;

class UpdateLineCommand 
{

	/**
	 * @var array
	 */
	public $lineId;

	/**
	 * @var array
	 */
	public $data;
	
	/**
	 * @param array $data
	 */
	public function __construct($lineId, $data){
		$this->lineId = $lineId;
		$this->data = $data;
	}

}
