<?php

namespace Platform\CollabBoard\Commands;

class GoogleInviteAcceptCommand 
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
     * @var array
     */
    public $data;

    /**
     * @param array $data
     * @param string $collabUrl
     * @param string $inviteCode
     */
	public function __construct($collabUrl, $inviteCode, $googleToken){
        $this->collabUrl = $collabUrl;
        $this->inviteCode = $inviteCode;
        $this->googleToken = $googleToken;
	}
}
