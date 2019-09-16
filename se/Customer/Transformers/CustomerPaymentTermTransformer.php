<?php

namespace Platform\Customer\Transformers;

use App\CustomerPaymentTerm;
use League\Fractal\TransformerAbstract;

class CustomerPaymentTermTransformer extends TransformerAbstract
{
    public function transform(CustomerPaymentTerm $paymentTerm)
    {
        return [
            'id' => $paymentTerm->id,
            'name' => (string)$paymentTerm->name,
        ];
    }
}