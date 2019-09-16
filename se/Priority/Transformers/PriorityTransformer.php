<?php

namespace Platform\Priority\Transformers;

use League\Fractal\TransformerAbstract;
use App\Priority;

class PriorityTransformer extends TransformerAbstract
{
    public function transform(Priority $Priority)
    {
    	//dd($Priority->toArray());
        $data = [
            'PriorityId' => (int)$Priority->id,
            'PriorityName' => (string)$Priority->priority
        ];

        return $data;
    }

}
