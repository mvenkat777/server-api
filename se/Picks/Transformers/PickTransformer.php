<?php

namespace Platform\Picks\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class PickTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pick)
	{
        $uploader = (new metausertransformer)->transform($pick->uploader);
        $favourites = $pick->favouritedUsers()->lists('id')->toArray();
        $comments = $pick->comments()->lists('id')->toArray();
        $isFavourited = in_array(\Auth::user()->id, $favourites);

        return [
            'id' => $pick->id,
            'name' => $pick->name,
            'counts' => [
                'favourites' => count($favourites),
                'comments' => count($comments),
            ],
            'isFavourited' => $isFavourited,
            'pick' => json_decode($pick->pick),
            'uploader' => $uploader,
        ];
	}

}
