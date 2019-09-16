<?php

namespace App\Http\Controllers;

use Platform\Forms\SignUp;


class validateSignUpController extends SignUp
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