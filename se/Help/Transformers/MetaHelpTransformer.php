<?php

namespace Platform\Help\Transformers;

use App\Help;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;


class MetaHelpTransformer extends TransformerAbstract
{ 
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Help $help)
    {
           
        $data = [
            'id' => $help->id,
            'title' =>  $help->title,
            'slug' =>  $help->slug,
        ];

        return $data;
    }
       
}
