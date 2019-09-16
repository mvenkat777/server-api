<?php

namespace Platform\Dashboard\Commands;

class GetNotificationFeedCommand 
{
    /**
     * @var App\User
     */
    public $user;

    /**
     * @var entity
     */
    public $entity;

	public function __construct($user, $entity){
        $this->user = $user;
        $this->entity = $entity;
	}

}
