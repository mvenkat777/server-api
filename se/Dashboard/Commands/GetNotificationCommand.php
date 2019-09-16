<?php

namespace Platform\Dashboard\Commands;

class GetNotificationCommand 
{
    public $user;

    public $items;

	public function __construct($user, $items = 20){
        $this->user = $user;
        $this->items = $items;
	}

}
