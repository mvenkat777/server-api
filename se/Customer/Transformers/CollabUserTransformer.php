<?php

namespace Platform\Customer\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class CollabUserTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($collabUser)
	{
        $data = [
            'id' => $collabUser->id,
            'displayName' => $collabUser->display_name,
            'email' => $collabUser->email,
        ];
        if (isset($collabUser->collabInvites) && count($collabUser->collabInvites) > 0) {
            $invite = $collabUser->collabInvites;
            $data['permission'] = $invite[0]['permission'];
            $data['isActive'] = $invite[0]['is_active']; 
            $data['accepted'] = empty($invite[0]['invite_code']); 
        } else {
            $data['permission'] = null;
            $data['isActive'] = null; 
            $data['accepted'] = null;
        }
        return $data; 
	}

}
