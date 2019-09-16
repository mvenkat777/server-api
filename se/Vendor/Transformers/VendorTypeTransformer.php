<?php

namespace Platform\Vendor\Transformers;

use App\VendorType;
use League\Fractal\TransformerAbstract;

class VendorTypeTransformer extends TransformerAbstract
{
    public function transform(VendorType $type)
    {
        return [
            'id' => $type->id,
            'name' => (string)$type->name,
        ];
    }
}