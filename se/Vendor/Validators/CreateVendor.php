<?php

namespace Platform\Vendor\Validators;

use Platform\App\Validation\DataValidator;

class CreateVendor extends DataValidator
 {
    protected $rules = [
        'code' => 'required|unique:vendors|min:4',
        'name' => 'required',
    ];
 }
