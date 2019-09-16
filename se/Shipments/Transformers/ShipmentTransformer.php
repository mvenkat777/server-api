<?php

namespace Platform\Shipments\Transformers;

use App\Shipment;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class ShipmentTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }
    public function transform(Shipment $shipment)
    {
        if($shipment->user != []){
            $user = $this->item($shipment->user, new MetaUserTransformer);
            $user = $this->manager->createData($user)->toArray();
        }
        return [
            'id' => (string)$shipment->id,
            'shipmentType' => (string)$shipment->shipment_type,
            'shippedDate' => $shipment->shipped_date,
            'shippedFrom' => (string)$shipment->shipped_from,
            'shippedDestination' => $shipment->shipped_destination,
            'itemDetails' => json_decode($shipment->item_details),
            'trackingId' => $shipment->tracking_id,
            'trackingProvider' => $shipment->tracking_provider,
            'trackingStatus' => $shipment->tracking_status,
            'userId' => $shipment->user_id,
            'user' => isset($user['data'])? $user['data']: [],
            'techpackID' => $shipment->techpack_id,
            'productId' => $shipment->product_id,
            'createdAt' => $shipment->created_at->toDateTimeString(),
            'updatedAt' => $shipment->updated_at->toDateTimeString()
        ];
    }
}
