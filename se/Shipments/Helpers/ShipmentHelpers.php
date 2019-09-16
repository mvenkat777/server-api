<?php

namespace Platform\Shipments\Helpers;

use Platform\App\Exceptions\SeException;

class ShipmentHelpers
{
	public static function transform($data)
    {
        $key = key($data);
        $schema = [
        	'shipmentType' => 'shipment_type',
        	'shippedDate' => 'shipped_date',
            'shippedFrom' => 'shipped_from',
            'shippedDestination' => 'shipped_destination',
            'trackingId' => 'tracking_id',
            'trackingProvider' => 'tracking_provider',
            'trackingStatus' => 'tracking_status',
            'updatedAt' => 'updated_at',
        	'createdAt' => 'created_at'
        ];
        if(!array_key_exists($key, $schema)){
            throw new SeException("Error Processing Request", 422, 4860104);
        }
        return [$schema[$key]=> $data[$key]];
    }
}