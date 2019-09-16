<?php

namespace App\Http\Controllers;

use Platform\Forms\Login;


class validateLoginController extends Login
{

	public $data;
	function __construct($formdata)
	{
		$this->data=$formdata;
		
	}

	function isValid()
	{
		return $this->validate($this->data);
	}

}