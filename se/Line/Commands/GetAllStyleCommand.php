<?php

namespace Platform\Line\Commands;

class GetAllStyleCommand 
{

	public function __construct($data){
		$this->item = isset($data['item']) ? $data['item'] : 50;
	}

}