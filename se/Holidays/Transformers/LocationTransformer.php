<?php

namespace Platform\Holidays\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class LocationTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($location)
	{
        $holidays = $this->collection($location->holidays, new MetaHolidayTransformer);
        $holidays = $this->manager->createData($holidays)->toArray()['data'];

        return [
            'id' => $location->id,
            'city' => $location->city,
            'state' => $location->state,
            'code' => $location->code,
            'country' => $location->country,
            'postalCode' => $location->postal_code,
            'address' => $location->address,
            'holidays' => $holidays
        ];
	}

}
