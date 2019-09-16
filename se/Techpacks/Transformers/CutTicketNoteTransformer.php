<?php

namespace Platform\Techpacks\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class CutTicketNoteTransformer extends TransformerAbstract 
{

    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform($cutTicketNote)
    {
        return [
            'note' => $cutTicketNote->note,
            'image' => json_decode($cutTicketNote->image),
        ];
    }

}
