<?php

namespace Platform\Vendor\Validators;

use Platform\App\Validation\DataValidator;

class BankValidator extends DataValidator
 {
    protected $rules = [
        'branchAddress' => 'sometimes',
        'swiftCode' => 'required',
        'nameOnAccount' => 'required',
        'bankName' => 'required',
        'accountNumber' => 'required',
        'accountType' => 'required',
        'address' => 'required'
    ];
 }