<?php

namespace Platform\Picks\Validators;

use Platform\App\Validation\DataValidator;

class UploadPick extends DataValidator
{
	/**
	 * Validation rulew
	 * @var array
	 */
    protected $rules = [
		"boardId" => "required",
		"title"   => "required",
		"image"   => "required",
    ];
}
