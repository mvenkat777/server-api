<?php

namespace Platform\Users\Transformers;

use App\UserTag;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\collection;
use Platform\Users\Transformers\UserTransformer;

class UserTagTransformer extends TransformerAbstract
{
    public function transform(UserTag $tag)
    {
    	$fractal = new Manager();
    	/**
         * It is been commented because, get-user-details-by-its-ID is giving maximum
         * execution time exception. It was crossing maximum excution time of 30 sec.
         */
    	// if(isset($tag->users)) {
     //        $users = new collection($tag->users, new UserTransformer);
     //        $users = $fractal->createData($users)->toArray()['data'];
     //    }
        
        $response = [
            'id' => $tag->id,
            'name' => $tag->name,
            'createdAt' => $tag->created_at->toDateTimeString(),
            'updatedAt' => $tag->updated_at->toDateTimeString()
        ];

        if (isset($users)) {
            $response['users'] = $users;
        }
        
        return $response;
    }
}
