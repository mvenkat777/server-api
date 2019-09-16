<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class SizeTypeTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($sizeType)
	{
		return [
			'id' => $sizeType->id,
			'sizeType' =>  strtoupper($sizeType->size_type),
		];
	}

}