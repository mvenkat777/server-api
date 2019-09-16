<?php

namespace Platform\Line\Transformers;

use App\SampleSubmission;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Line\Transformers\StyleDevelopmentTransformer;
use Platform\Line\Transformers\StyleProductionTransformer;
use Platform\Line\Transformers\StyleShippedTransformer;
use Platform\Orders\Transformers\MetaOrderTransformer;
use Platform\SampleContainer\Transformers\MetaSampleContainerTransformer;
use Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer;
use Platform\TNA\Models\TNA;
use Platform\TNA\Transformers\MetaTNATransformer;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;

class MetaStyleTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($style)
	{
		$style = [
			'id' => $style->id,
			'code' => $style->code,
			'name' => $style->name,
			'archivedAt' => isset($style->archived_at)? $style->archived_at : NULL,
            'completedAt' => is_null($style->completed_at)? NULL : $style->completed_at->toDateTimeString(),
		];
		return $style;
	}

}
