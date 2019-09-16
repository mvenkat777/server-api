<?php

namespace Platform\TNA\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\TNA\Models\TNAType;

class TNATypeTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(TNAType $tnaState)
	{
		return [
			'id' => $tnaState->id,
			'type' => $tnaState->type
		];
	}

}