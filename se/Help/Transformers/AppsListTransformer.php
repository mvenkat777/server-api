<?php

namespace Platform\Help\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Help\Transformers\MetaHelpTransformer;
use app\AppsList;


class AppsListTransformer extends TransformerAbstract
{ 

    public function transform(AppsList $app)
    {
        $fractal = new Manager();
        
            $data = [
                'id' => $app->id,
                'appName' =>  $app->app_name,
                'icon' =>  $app->icon,
            ];
    
         return $data;
    } 
       
}
