<?php

namespace Platform\Vendor\Transformers;

use App\Vendor;
use League\Fractal\TransformerAbstract;

class MetaVendorTransformer extends TransformerAbstract
{
    public function transform(Vendor $vendor)
    {
        return [
            'vendorId' => (string)$vendor->id,
            'code' => (string)$vendor->code,
            'name' => (string)$vendor->name,
            'businessEntity' => (string)$vendor->business_entity,
            'countryCode' => $vendor->country_code,
            'country' => is_null($vendor->country)? NULL : $vendor->country['country'],
            'archivedAt' => is_null($vendor->archived_at)? NULL :$vendor->archived_at->toDateTimeString(),
            'createdAt' => $vendor->created_at->toDateTimeString(),
            'updatedAt' => $vendor->updated_at->toDateTimeString()
        ];
    }
}
