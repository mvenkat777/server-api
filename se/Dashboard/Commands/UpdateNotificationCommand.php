<?php

namespace Platform\Dashboard\Commands;

class UpdateNotificationCommand 
{
    public $user;

    public $items;

    public $notificationId;

	public function __construct($user, $items = 20, $notificationId){
        $this->user = $user;
        $this->items = $items;
        $this->notificationId = $notificationId;
	}

}
