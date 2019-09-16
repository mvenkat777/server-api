<?php

namespace Platform\Vendor\Transformers;

use App\VendorPaymentTerm;
use League\Fractal\TransformerAbstract;

class VendorPaymentTermTransformer extends TransformerAbstract
{
    public function transform(VendorPaymentTerm $paymentTerm)
    {
        return [
            'id' => $paymentTerm->id,
            'name' => (string)$paymentTerm->name,
        ];
    }
}