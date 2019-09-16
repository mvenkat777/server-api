<?php

namespace Platform\Pom\Commands;

class CheckPomCommand
{
	/**
	 * @var string
	 */
    public $category;

    /**
	 * @var string
	 */
    public $product;

    /**
	 * @var string
	 */
    public $sizeType;

    /**
	 * @var string
	 */
    public $productType;


    /**
	 * @var array
	 */
	public $brands;
	
	function __construct($category, $sizeType, $productType, $product)
    {	
        $this->category = $category;
        $this->product = $product;
        $this->sizeType = $sizeType;
        $this->productType = $productType;
    } 
}