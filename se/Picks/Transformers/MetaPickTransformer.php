<?php

namespace Platform\Picks\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaPickTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pick)
	{
        return [
            'id' => $pick->id,
            'name' => $pick->name,
            'pick' => json_decode($pick->pick),
        ];
	}

}
