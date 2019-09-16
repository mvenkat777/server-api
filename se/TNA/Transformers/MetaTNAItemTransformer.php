<?php

namespace Platform\TNA\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\TNA\Models\TNAItem;

class MetaTNAItemTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(TNAItem $tna)
	{
		return [
			'id' => $tna->id,
            'title' => $tna->title,
            'plannedDate' => $tna->planned_date
		];
	}

}
