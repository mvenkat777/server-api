<?php

namespace Platform\Address\Transformers;

use App\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{
    public function transform(Address $address)
    {
        return [
            'id' => (string)$address->id,
            'label' => (string)$address->label,
            'line1' => (string)$address->line1,
            'line2' => (string)$address->line2,
            'city' => (string)$address->city,
            'state' => (string)$address->state,
            'zip' => (string)$address->zip,
            'country' => (string)$address->country,
            'airCargoPort' => (string)$address->air_cargo_port,
            'seaCargoPort' => (string)$address->sea_cargo_port,
            'phone' => (string)$address->phone,
            'isPrimary' => (boolean)$address->is_primary
        ];
    }
}
