<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class PomForTechpackTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pomSheet)
	{
		return [
			'pomId' => $pomSheet->id,
			'qc' => $pomSheet->qc,
			'key' => $pomSheet->key,
			'pomCode' => $pomSheet->code,
			'description' => $pomSheet->description,
			'tol' => $pomSheet->tol,
			'data' => json_decode($pomSheet->data),
		];
	}

}