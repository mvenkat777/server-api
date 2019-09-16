<?php

namespace Platform\Users\Commands;

class GetUserTagCommand
{
	/**
	 * @var string
	 */
    public $userId;

     /**
     * @param $userId
     */
    function __construct($userId)
    {
    	$this->userId = $userId;
    }
}