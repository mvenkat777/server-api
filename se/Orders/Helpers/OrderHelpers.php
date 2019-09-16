<?php

namespace Platform\Orders\Helpers;

use Platform\App\Exceptions\SeException;

class OrderHelpers
{
	public static function transform($data)
    {
        $key = key($data);
        $schema = [
        	'code' => 'code',
        	'label' => 'label',
        	'quantity' => 'quantity',
        	'value' => 'value',
        	'size' => 'size',
        	'expectedDeliveryDate' => 'expected_delivery_date',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];
        if(!array_key_exists($key, $schema)){
            throw new SeException("Error Processing Request", 422, 4560104);
        }
        return [$schema[$key]=> $data[$key]];
    }
}