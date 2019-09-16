<?php

namespace Platform\Customer\Validators;

use Platform\App\Validation\DataValidator;

class CreateCustomer extends DataValidator
 {
    protected $rules = [
        'code' => 'required|unique:customers|min:4',
        'name' => 'required',
    ];
 }
