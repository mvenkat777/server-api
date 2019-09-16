<?php

namespace Platform\Dashboard\Commands;

class GetAppFeedCommand 
{
    public $appName;

    public $items;

    public $user;

	public function __construct($appName, $user = null, $items = 20){
        $this->appName = $appName;
        $this->items = is_numeric($user) ? $user : $items;
        $this->user = is_null($user) || is_numeric($user) ? \Auth::user() : $user;
	}

}
