<?php

namespace Platform\Holidays\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaHolidayTransformer extends TransformerAbstract 
{
	public function transform($holiday)
	{
        return [
            'id' => $holiday->id,
            'date' => $holiday->date,
            'weekDay' => $holiday->day,
            'isWorkDay' => $holiday->isWorkDay,
            'affectedSupplyChain' => $holiday->affected_supply_chain,
            'description' => $holiday->description,
            'locationId' => $holiday->location_id,
            'year' => $holiday->year
        ];
	}

}
