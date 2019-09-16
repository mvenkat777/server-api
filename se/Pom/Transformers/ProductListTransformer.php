<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class ProductListTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($productList)
	{		
        return [
            'code' => $productList->code,
            'product' => Helpers::snakecaseToNormalcase($productList->product),
            'productType' => Helpers::snakecaseToNormalcase($productList->productType['product_type']),
            'description' => $productList->description,
        ];
	}

}
