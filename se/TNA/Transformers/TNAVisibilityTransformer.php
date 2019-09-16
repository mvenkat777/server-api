<?php

namespace Platform\TNA\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\TNA\Models\TNAItemVisibility;

class TNAVisibilityTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(TNAItemVisibility $tnaVisibility)
	{
		return [
			'id' => $tnaVisibility->id,
			'visibility' => $tnaVisibility->visibility
		];
	}

}