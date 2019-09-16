<?php

namespace Platform\Customer\Transformers;

use App\Customer;
use League\Fractal\TransformerAbstract;

class MetaCustomerTransformer extends TransformerAbstract
{
    public function transform(Customer $customer)
    {
        return [
            'customerId' => (string)$customer->id,
            'code' => (string)$customer->code,
            'name' => (string)$customer->name,
            'businessEntity' => (string)$customer->business_entity,
            'archivedAt' => is_null($customer->archived_at)? NULL :$customer->archived_at->toDateTimeString(),
            'createdAt' => $customer->created_at->toDateTimeString(),
            'updatedAt' => $customer->updated_at->toDateTimeString(),
            'archivedAt' => isset($customer->archived_at)?$customer->archived_at->toDateTimeString():NULL
        ];
    }
}
