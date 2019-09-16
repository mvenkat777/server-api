<?php

namespace Platform\Line\Commands;

class AttachTechpackCommand 
{
	/**
	 * @var array
	 */
	public $data;
	
	/**
	 * @param array $data
	 */
	public function __construct($data){
		$this->data = $data;
	}
}
