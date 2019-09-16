<?php

namespace Platform\CollabBoard\Commands;

class GetInvitedUserCommand 
{

    /**
     * @var string
     */
    public $collabUrl;

    /**
     * @var string
     */
    public $inviteCode;

    /**
     * @param string$collabUrl
     * @param string $inviteCode
     */
	public function __construct($collabUrl, $inviteCode){
        $this->collabUrl = $collabUrl;
        $this->inviteCode = $inviteCode;
	}

}
