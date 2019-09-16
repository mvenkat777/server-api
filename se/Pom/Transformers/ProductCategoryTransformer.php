<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;
use Platform\Pom\Transformers\ClassificationTransformer;

class ProductCategoryTransformer extends TransformerAbstract 
{

	public function __construct()
	{
            $this->manager = new Manager();
	}

	public function transform($productCategory)
	{
            $data = [
                'code' => $productCategory->code,
                'category' => Helpers::snakecaseToNormalcase($productCategory->category),
                'description' => $productCategory->description,
                // 'classification' => !empty($productList->classification)? 
                //     Helpers::snakecaseToNormalcase(
                //         $productList->classification['classification']
                //     ) 
                //     : NULL,
            ];
            return $data;
	}

}
