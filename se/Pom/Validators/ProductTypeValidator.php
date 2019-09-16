<?php 

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class ProductTypeValidator extends DataValidator
{
    protected $rules = [];

    /**
     * Validation rules for adding a new category
     *
     * @return void
     */
    public function setCreateProductTypeRules() {
        $this->rules= [
            'productType' => 'required|unique:product_types,product_type'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a category
     *
     * @return void
     */
    public function setUpdateProductTypeRules() {
        $this->rules= [
            'productType' => 'required'
        ];
        return $this;
    }    

}
