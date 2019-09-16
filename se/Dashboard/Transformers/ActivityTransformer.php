<?php

namespace Platform\Dashboard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Activity\Models\GlobalActivity;

class ActivityTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(GlobalActivity $activity)
    {
        $links = $activity->links;
        foreach ($links as $key => $link) {
            // if(!isset($link['originalValue'])){
            //     dd($link);
            // }
            if(isset($link['originalValue']) && is_string($link['originalValue']) && json_decode($link['originalValue'])) {
                $links[$key]['originalValue'] = json_decode($link['originalValue']);
            }
            if(isset($link['updatedValue']) && is_string($link['updatedValue']) && json_decode($link['updatedValue'])) {
                $links[$key]['updatedValue'] = json_decode($link['updatedValue']);
            }
        }
      
        $activity->links = $links;
    	return $activity;
    }
}

