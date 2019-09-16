<?php

namespace Platform\Customer\Transformers;

use App\CustomerType;
use League\Fractal\TransformerAbstract;

class CustomerTypeTransformer extends TransformerAbstract
{
    public function transform(CustomerType $type)
    {
        return [
            'id' => $type->id,
            'name' => (string)$type->name,
        ];
    }
}