<?php

namespace Platform\Users\Commands;

class GetNoteCommand
{
	/**
     * @var string
     */
	Public $token;

	/**
     * @var string
     */
	Public $userId;

	/**
     * @param $token
     */
	function __construct($token, $userId)
	{
		$this->token =$token;
		$this->userId =$userId;
	}
}