<?php

namespace Platform\Materials\Validators;

use Platform\App\Validation\DataValidator;

class MaterialsLibraryValidator extends DataValidator
 {
    protected $rules = [
        'materialId' => 'required',
        'vendorId' => 'required',
        'costLocal' => 'required',
        'costUsd' => 'required',
        'costUom' => 'required',
        'stock' => 'required'
        // 'fabricLeadTime' => 'required|numeric',
        // 'minimumOrderQuantity' => 'required',
        // 'minimumOrderQuantitySurcharge' => 'required',
        // 'minimumColorQuantity' => 'required',
        // 'minimumColorQuantitySurcharge' => 'required'
    ];
 }