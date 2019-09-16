<?php

namespace Platform\CollabBoard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class InvitedUserTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($user)
	{
        return [
            'displayName' => $user->display_name,
            'email' => $user->email,
            'signUpRequired' => (bool)(!$user->is_active),
        ];
	}

}
