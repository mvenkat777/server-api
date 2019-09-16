<?php

namespace Platform\Collab\Transformers;

use League\Fractal\Manager;
use Platform\Collab\Models\Collab;
use Platform\Collab\Transformers\CardTransformer;
use League\Fractal\TransformerAbstract;

class CollabTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform($collab)
    {
        // $cards = [];
        // if(isset($collab['cards'])) {
        //     $cards = $this->collection($collab['cards'], new CardTransformer);
        //     $cards = $this->manager->createData($cards)->toArray()['data'];
        //     if(!count($cards[0]))
        //     {
        //         unset($cards[0]);
        //         $cards = array_values($cards);
        //     }
        // }
        $data = [
            'collabId' => $collab['collabId'],  
            'title' => $collab['title'],
            'seen' => $collab['seen'],
            'isPublic' => $collab['isPublic'],
            // 'cards' => $cards,
            'isAuthorised' => isset($collab['isAuthorised'])?$collab['isAuthorised']:true,
            'createdAt' => $collab['createdAt']
        ];
        return $data;
    }
}
