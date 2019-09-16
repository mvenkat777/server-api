<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class SizeTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($size)
	{
		return [
			'code' => $size->code,
				'code' => $size->code,
                'size' => Helpers::snakecaseToNormalcase($size->size),
                'sizeType' => Helpers::snakecaseToNormalcase(
                    $size->sizeType['size_type']
                ),
		];
	}

}