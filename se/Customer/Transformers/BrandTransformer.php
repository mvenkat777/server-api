<?php

namespace Platform\Customer\Transformers;

use App\CustomerBrand;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract
{
    public function transform(CustomerBrand $brand)
    {
        return [
            'id' => (string)$brand->id,
            'customerId' => (string)$brand->customer_id,
            'brand' => (string)$brand->brand
        ];
    }
}
