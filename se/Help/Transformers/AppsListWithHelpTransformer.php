<?php

namespace Platform\Help\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Help\Transformers\MetaHelpTransformer;
use app\AppsList;


class AppsListWithHelpTransformer extends TransformerAbstract
{ 

    public function transform(AppsList $app)
    {
        $fractal = new Manager();

        $help = NULL;

        if($app->help) {
            $help = new Collection($app->help, new MetaHelpTransformer());
            $help = $fractal->createData($help)->toArray()['data'];
        }

        $data = [
            'id' => $app->id,
            'appName' =>  $app->app_name,
            'icon' =>  $app->icon,
            'toggleFlag'=>'False',
            'topics' => $help
        ];

        return $data;
    }
       
}
