<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class SizeRangeTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($sizeRange)
	{
		return [
			'code' => $sizeRange->code,
			'range' => strtoupper($sizeRange->range),
			'value' => explode(',', strtoupper(implode(',', json_decode($sizeRange->range_value)))),
			'sizeType' => $sizeRange->sizeType['size_type']
		];
	}

}