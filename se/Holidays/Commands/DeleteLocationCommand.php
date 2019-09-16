<?php

namespace Platform\Holidays\Commands;

class DeleteLocationCommand 
{

	public function __construct($locationId){
        $this->locationId = $locationId;
	}

}
