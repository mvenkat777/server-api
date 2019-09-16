<?php

namespace Platform\Help\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Help\Transformers\MetaHelpTransformer;
use app\Help;


class GetTopicsWithHelpTransformer extends TransformerAbstract
{ 

    public function transform(Help $help)
    {
        $fractal = new Manager();

        // $help = NULL;

        
            $h = new Collection($help, new GetTopicsWithHelpTransformer());
            
            //dd($h);
            $data = [
                   'id' => $help->id,
                   'title' =>  $help->title,
                   'slug' =>  $help->slug,
                ];

                return $data;
    }
       
}
