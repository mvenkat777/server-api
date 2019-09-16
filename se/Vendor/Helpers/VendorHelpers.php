<?php

namespace Platform\Vendor\Helpers;

use Platform\App\Exceptions\SeException;

class VendorHelpers
{
	public static function transform($data)
    {
        $key = key($data);
        $schema = [
        	'code' => 'code',
        	'name' => 'name',
            'businessEntity' => 'business_entity',
            'createdAt' => 'created_at',
        	'updatedAt' => 'updated_at',
            'q' => 'all'
        ];
        if(!array_key_exists($key, $schema)){
            throw new SeException("Error Processing Request", 422, 7860104);
        }
        return [$schema[$key]=> $data[$key]];
    }
}