<?php

namespace Platform\Holidays\Commands;

class UpdateLocationCommand 
{

	public function __construct($data, $locationId){
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->country = $data['country'];
        $this->code = $data['code'];
        $this->postalCode = $data['postalCode'];
        $this->address = isset($data['address']) ? $data['address'] : null;
        $this->locationId = $locationId;
	}

}
