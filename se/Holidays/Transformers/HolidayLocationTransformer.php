<?php

namespace Platform\Holidays\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Holidays\Transformers\MetaHolidayTransformer;

class HolidayLocationTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($holiday)
	{
        $location = [
            'id' => $holiday->id,
            'city' => $holiday->city,
            'state' => $holiday->state,
            'country' => $holiday->country,
            'postalCode' => $holiday->postal_code,
            'address' => $holiday->address,
        ];
        return [
            'id' => $holiday->id,
            'date' => $holiday->date,
            'weekDay' => $holiday->day,
            'isWorkDay' => $holiday->is_work_day,
            'affectedSupplyChain' => $holiday->affected_supply_chain,
            'description' => $holiday->description,
            'year' => $holiday->year,
            'location' => $location
            
        ];
	}

}
