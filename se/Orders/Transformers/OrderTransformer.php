<?php

namespace Platform\Orders\Transformers;

use App\Order;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;
use Platform\Vendor\Transformers\MetaVendorTransformer;

class OrderTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }
    public function transform(Order $order)
    {
        $vendors = $this->collection($order->vendors, new MetaVendorTransformer);
        $vendors = $this->manager->createData($vendors)->toArray();

        $techpacks = $this->collection($order->techpacks, new MetaTechpackTransformer);
        $techpacks = $this->manager->createData($techpacks)->toArray();

        $customer = $this->item($order->customer, new MetaCustomerTransformer);
        $customer = $this->manager->createData($customer)->toArray();
        
        return [
            'id' => (string)$order->id,
            'code' => (string)$order->code,
            'label' => (string)$order->label,
            'customerId' => (string)$order->customer_id,
            'customer' => $customer['data'],
            'value' => $order->value,
            'quantity' => $order->quantity,
            'size' => $order->size,
            'ExpectedDeliveryDate' => $order->expected_delivery_date,
            'vendors' => $vendors['data'],
            'techpacks' => $techpacks['data'],
            'createdAt' => $order->created_at->toDateTimeString(),
            'updatedAt' => $order->updated_at->toDateTimeString()
        ];
    }
}
