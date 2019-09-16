<?php

namespace Platform\Holidays\Commands;

class UpdateHolidayCommand 
{

	public function __construct($data, $holidayId){
        $this->date = $data['date'];
        $this->weekDay = isset($data['weekDay']) ? $data['weekDay'] : null;
        $this->isWorkDay = isset($data['isWorkDay']) ? $data['isWorkDay'] : true;
        $this->affectedSupplyChain = isset($data['affectedSupplyChain']) ? $data['affectedSupplyChain'] : 'all';
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->holidayId = $holidayId;
	}

}
