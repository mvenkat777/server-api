<?php 

namespace Platform\Contacts\Validators;

use Platform\App\Validation\DataValidator;

class Contacts extends DataValidator
{
	protected $rules = [
	    'email'=>'',
	    'mobileNumber1'=>'',
	    'mobileNumber2'=>'',
	    'mobileNumber3'=>'',
	    'email1'=>'',
	    'email2'=>'',
	    'skypeId'=>'',
	];
}
