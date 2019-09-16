<?php

namespace Platform\Holidays\Commands;

class DeleteHolidayCommand 
{

	public function __construct($holidayId){
        $this->holidayId = $holidayId;
	}

}
