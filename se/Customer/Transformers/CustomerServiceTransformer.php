<?php

namespace Platform\Customer\Transformers;

use App\CustomerService;
use League\Fractal\TransformerAbstract;

class CustomerServiceTransformer extends TransformerAbstract
{
    public function transform(Customerservice $service)
    {
        return [
            'id' => $service->id,
            'name' => (string)$service->name
        ];
    }
}