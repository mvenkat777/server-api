<?php 

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class ProductListValidator extends DataValidator
{
    protected $rules = [];

    /**
     * Validation rules for adding a new category
     *
     * @return void
     */
    public function setCreateProductRules() {
        $this->rules= [
            'product' => 'required|unique:product_lists',
            'productTypeCode' => 'sometimes|exists:product_types,code'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a category
     *
     * @return void
     */
    public function setUpdateProductRules() {
        $this->rules= [
            'product' => 'required|unique:product_lists',
            'productTypeCode' => 'sometimes|exists:product_types,code'
        ];
        return $this;
    }    

}
