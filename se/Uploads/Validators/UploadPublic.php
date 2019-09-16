<?php

namespace Platform\Uploads\Validators;

use Platform\App\Validation\DataValidator;

class UploadPublic extends DataValidator
{
	/**
	 * @var array
	 */
	protected $rules = [
 		'isPublic' => 'sometimes|boolean'
 	];
}  