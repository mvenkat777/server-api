<?php

namespace Platform\Line\Commands;

class GetLineByIdCommand 
{
	public $lineId;
	
	/**
	 * @param string $lineId
	 */
	public function __construct($lineId){
		$this->lineId = $lineId;
	}

}
