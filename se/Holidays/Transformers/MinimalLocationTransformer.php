<?php

namespace Platform\Holidays\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Holidays\Models\Location;

class MinimalLocationTransformer extends TransformerAbstract 
{

	public function transform(Location $location)
	{
        return [
            'id' => $location->id,
            'code' => $location->code,
        ];
	}

}
