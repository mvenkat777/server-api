<?php

namespace Platform\Techpacks\Validators;

use Platform\App\Validation\DataValidator;

class NewComment extends DataValidator
{
    protected $rules = [
        'comment' => 'required',
        'userId' => 'required|exists:users,id',
    ];
}
