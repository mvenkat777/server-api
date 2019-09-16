<?php

namespace Platform\Collab\Commands;

class GetConversationHistoryCommand 
{
    public $user;

	public function __construct($user = null){
        $this->user = is_null($user) ? \Auth::user() : $user;
	}

}
