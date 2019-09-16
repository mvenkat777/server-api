<?php

namespace Platform\Line\Commands;

class CreateNewStyleCommand 
{
	/**
	 * @var string
	 */
	public $lineId;
	
	/**
	 * @var array
	 */
	public $data;

	/**
	 * @param string $lineId
	 */
	public function __construct($lineId, $data){
            $this->lineId = $lineId;
            $this->data = $data;
	}

}
