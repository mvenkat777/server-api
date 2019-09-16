<?php

namespace Platform\Customer\Transformers;

use App\CustomerRequirement;
use League\Fractal\TransformerAbstract;

class CustomerRequirementTransformer extends TransformerAbstract
{
    public function transform(CustomerRequirement $requirement)
    {
        return [
            'id' => $requirement->id,
            'name' => (string)$requirement->name,
        ];
    }
}