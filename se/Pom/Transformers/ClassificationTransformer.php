<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class ClassificationTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($classification)
	{
		$data = [
                'code' => $classification->code,
                'classification' => Helpers::snakecaseToNormalcase($classification->classification),
            ];
            return $data;
	}

}