<?php

namespace Platform\NamingEngine\Commands;

class GenerateStyleCodeCommand 
{
    /**
     * @var string
     */
    public $customerCode;

    /**
     * @var string
     */
    public $productCategory;

    /**
     * @var string
     */
    public $product;

    public function __construct($customerCode, $productCategory, $product){
        $this->customerCode = $customerCode;
        $this->productCategory = strtolower($productCategory);
        $this->product = strtolower($product);
    }

}
