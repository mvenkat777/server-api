<?php

namespace Platform\Contacts\Commands;

class GetContactByUserIdCommand
{

    /**
     * @var integer
    */
    public $userId;

    /**
     * @var integer
    */
    public $contactId;
    
    function __construct($userId, $contactId)
    {
        $this->userId = $userId;
		$this->contactId = $contactId;
    }


}