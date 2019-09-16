<?php

namespace Platform\Materials\Validators;

use Platform\App\Validation\DataValidator;

class MaterialsValidator extends DataValidator
 {
    protected $rules = [
        'materialType' => 'required',
        'construction' => 'required',
        'constructionType' => 'required',
        'fabricType' => 'required',
        'fiber1' => 'required',
        'fiber1Percentage' => 'required|numeric|max:100',
        'weight' => 'required',
        'weightUOM' => 'required'
    ];
 }