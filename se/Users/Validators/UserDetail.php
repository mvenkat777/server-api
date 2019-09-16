<?php

namespace Platform\Users\Validators;

use Platform\App\Validation\DataValidator;

class UserDetail extends DataValidator
 {
    protected $rules = [
        'firstName' => 'max:40',
        'lastName' => 'max:40',
        'country' => 'max:40',
        'city' => 'max:40',
        'state' => 'max:40',
        'mobileNumber' => 'max:15'
    ];
 }
