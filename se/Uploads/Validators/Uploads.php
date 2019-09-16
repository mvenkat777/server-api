<?php

namespace Platform\Uploads\Validators;

use Platform\App\Validation\DataValidator;

class Uploads extends DataValidator
{	
	/**
	 * @var array
	 */
	protected $rules = [
 		'isPublic' => 'sometimes|boolean',
 		'files' => 'required|array',
 		'description' => 'sometimes|string',
 		'bucket' => 'sometimes|string',
 		'folder' => 'sometimes|string'
 	];
}  