<?php

namespace Platform\Collab\Transformers;

use League\Fractal\Manager;
use Platform\Collab\Models\Card;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform($card)
    {
        if(!count($card)){
            return $card;
        }
        return [
            'id'=> $card['id'],
            'data'=> $card['data'],
            'type'=> $card['type'],
            'members'=> $card['members'],
            'owner'=> $card['owner'],
            'isFavourite' => isset($card['isFavourite'])?$card['isFavourite']:false,
            'isMedia' => isset($card['isMedia'])?$card['isMedia']:false,
            'isAuthorised' => isset($card['isAuthorised'])?$card['isAuthorised']:false,
            'totalComments' => isset($card['totalComments'])?$card['totalComments']:0,
            'urlMeta' => isset($card['urlMeta'])?$card['urlMeta']:NULL,
            'isEdited'=> isset($card['isEdited'])?$card['isEdited']:false,
            'collabId' => $card['collabId'],
            'createdAt'=> $card['createdAt']    
        ];
    }
}

