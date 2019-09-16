<?php

namespace Platform\Authentication\Commands;

class GetUserByTokenCommand 
{
    /*
     * @var string
     */
    public $token;

	public function __construct($token){
        $this->token = $token;
	}

}
