<?php

namespace Platform\Vendor\Transformers;

use App\VendorService;
use League\Fractal\TransformerAbstract;

class VendorServiceTransformer extends TransformerAbstract
{
    public function transform(Vendorservice $service)
    {
        return [
            'id' => $service->id,
            'name' => (string)$service->name
        ];
    }
}