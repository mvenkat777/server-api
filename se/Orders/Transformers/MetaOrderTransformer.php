<?php

namespace Platform\Orders\Transformers;

use App\Order;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaOrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'id' => (string)$order->id,
            'code' => (string)$order->code,
            'label' => (string)$order->label,
            'quantity' => $order->quantity,
            'sizeRange' => $order->size
        ];
    }
}
