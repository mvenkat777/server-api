<?php

namespace Platform\Help\Transformers;

use App\Help;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;


class HelpTransformer extends TransformerAbstract
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
            'description' =>  $help->description,
            'like' =>  $help->like,
            'dislike' =>  $help->dislike,
            'feedback' =>  json_decode($help->feedback),
            'authorLog' =>  json_decode($help->author_log),
            'appName' =>  $help->appList->app_name,
            'appIcon' =>  $help->appList->icon,
            'createdAt' => $help->created_at->toDateTimeString(),
            'updatedAt' => $help->updated_at->toDateTimeString()
        ];

        return $data;
    }

}
