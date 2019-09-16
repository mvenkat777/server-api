<?php

namespace Platform\Users\Commands;

class AddTagCommand
{
	/**
	 * @var string
	 */
    public $name;

    /**
	 * @var string
	 */
    public $token;

    /**
	 * @var string
	 */
    public $userId;

     /**
     * @param array $data and $token
     */
    function __construct($data , $token)
    {
    	$this->name = $data['tag'];
        $this->token = $token;
    	$this->userId = $data['userId'];
    }
}
