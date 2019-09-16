<?php

namespace Platform\CollabBoard\Commands;

class LegacyInviteAcceptCommand 
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $collabUrl;

    /**
     * @var string
     */
    public $inviteCode;

    /**
     * @param array $data
     * @param string $collabUrl
     * @param string $inviteCode
     */
	public function __construct($collabUrl, $inviteCode, $data){
        $this->data = $data;
        $this->collabUrl = $collabUrl;
        $this->inviteCode = $inviteCode;
	}

}
