<?php

namespace Platform\Line\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class LineMinimalTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($line)
	{
            return [
                'id' => $line->id,
                'code' => $line->code,
                'name' => $line->name,
                'archivedAt' => is_null($line->archived_at)? NULL : $line->archived_at->toDateTimeString(),
                'completedAt' => is_null($line->completed_at)? NULL : $line->completed_at->toDateTimeString(),
            ];
	}

}
