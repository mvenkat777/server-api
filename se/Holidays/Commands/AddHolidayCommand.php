<?php

namespace Platform\Holidays\Commands;

class AddHolidayCommand 
{

	public function __construct($data, $locationId){
        $this->date = $data['date'];
        $this->weekDay = isset($data['weekDay']) ? $data['weekDay'] : null;
        $this->isWorkDay = isset($data['isWorkDay']) ? $data['isWorkDay'] : true;
        $this->affectedSupplyChain = isset($data['affectedSupplyChain']) ? $data['affectedSupplyChain'] : 'all';
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->year = isset($data['year']) ? (int)$data['year'] : $this->getYear($this->date);
        $this->locationId = $locationId;
	}

    private function getYear($date)
    {
        return (int)explode('-', $date)[0];
    }

}
