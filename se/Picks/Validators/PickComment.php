<?php

namespace Platform\Picks\Validators;

use Platform\App\Validation\DataValidator;

class PickComment extends DataValidator
{
    /**
     * Validation rulew
     * @var array
     */
    protected $rules = [
        "pickId"  	=> "required",
        "userId"  	=> "required",
        "comment"   => "required",
    ];
}
