<?php

namespace Platform\Help\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Help\Transformers\MetaHelpTransformer; 
use app\Help;


class LikeAndDislikeTransformer extends TransformerAbstract
{ 

    public function transform(Help $slug)
    {
        $fractal = new Manager();

        // $help = NULL;

        
            $h = new Collection($slug, new LikeAndDislikeTransformer());
            
            //dd($h);
            $data = [
                   'like'=>$slug->like,
                   'dislike' =>  $slug->dislike
                   
                ];

                return $data;
    }
       
}
