<?php

namespace Platform\Techpacks\Validators;

use Platform\App\Validation\DataValidator;

class NewColorway extends DataValidator
{
    protected $rules = [
        'techpackId' => 'required|exists:techpacks,id',
    ];
}
