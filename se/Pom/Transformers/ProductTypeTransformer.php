<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class ProductTypeTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($productType)
	{
            return [
                'code' => $productType->code,
                'productType' => Helpers::snakecaseToNormalcase($productType->product_type),
            ];
	}

}
