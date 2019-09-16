<?php

namespace Platform\Users\Commands;

class SearchAllUserCommand
{
	public $count;
	function __construct($data)
	{
		$this->count = isset($data['item'])? $data['item'] : 100;
	}
}