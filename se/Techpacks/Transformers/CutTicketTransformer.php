<?php

namespace Platform\Techpacks\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class CutTicketTransformer extends TransformerAbstract 
{

    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform($cutTicket)
    {
        return [
            'id' => $cutTicket->id,
            'name' => $cutTicket->name,
            'image' => json_decode($cutTicket->image),
            'amount' => $cutTicket->amount,
            'fabric' => $cutTicket->fabric,
            'nonFlip' => $cutTicket->non_flip,
            'x' => $cutTicket->x,
            'y' => $cutTicket->y,
            'xy' => $cutTicket->xy,
        ];
    }

}
