<?php

namespace Platform\Holidays\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Holidays\Models\Location;

class MetaLocationTransformer extends TransformerAbstract 
{

	public function transform(Location $location)
	{
        $dbYears = \Platform\Holidays\Models\Holiday::where('location_id', $location->id)->select('year')->get()->toArray();
        $years = [];

        foreach($dbYears as $year) {
            $years[] = $year['year'];
        }
        $years = array_values(array_unique(array_filter($years)));

        return [
            'id' => $location->id,
            'city' => $location->city,
            'state' => $location->state,
            'country' => $location->country,
            'code' => $location->code,
            'postalCode' => $location->postal_code,
            'address' => $location->address,
            'years' => $years
        ];
	}

}
