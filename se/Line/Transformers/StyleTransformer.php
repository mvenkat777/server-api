<?php

namespace Platform\Line\Transformers;

use App\SampleSubmission;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Line\Transformers\StyleDevelopmentTransformer;
use Platform\Line\Transformers\StyleProductionTransformer;
use Platform\Line\Transformers\StyleShippedTransformer;
use Platform\Line\Transformers\StyleReviewTransformer;
use Platform\Orders\Transformers\MetaOrderTransformer;
use Platform\SampleContainer\Transformers\MetaSampleContainerTransformer;
use Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer;
use Platform\TNA\Models\TNA;
use Platform\TNA\Transformers\MetaTNATransformer;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;

class StyleTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($style)
	{
		if ($style->techpack) {
            $techpack = (new MetaTechpackTransformer())->transform($style->techpack);
		} else {
            $techpack = null;
            $relatedSamples = null;
		}

		if ($style->tna) {
			$tna = (new MetaTNATransformer())->transform($style->tna);
		} else {
			$tna = null;
		}

		if ($style->sampleContainer) {
            $sampleContainer = (new MetaSampleContainerTransformer())->transform($style->sampleContainer);
		} else {
            $sampleContainer = null;
		}

		if ($style->order) {
			$order = (new MetaOrderTransformer())->transform($style->order);
		} else {
			$order = null;
		}
		if ($style->development) {
			$development = $this->collection($style->development, new StyleDevelopmentTransformer);
    	    $development = $this->manager->createData($development)->toArray()['data'];
		} else {
			$development = [];
		}

		if ($style->production) {
	        $production = $this->collection($style->production, new StyleProductionTransformer);
	        $production = $this->manager->createData($production)->toArray()['data'];
	    } else {
			$production = [];
		}

		if($style->shipped) {
	        $shipped = $this->collection($style->shipped, new StyleShippedTransformer);
	        $shipped = $this->manager->createData($shipped)->toArray()['data'];
	    } else {
			$shipped = [];
		}

		if($style->review) {
	        $review = $this->collection($style->review, new StyleReviewTransformer);
	        $review = $this->manager->createData($review)->toArray()['data'];
	    } else {
			$review = [];
		}
		$style = [
			'id' => $style->id,
			'code' => $style->code,
			'name' => $style->name,
			'line' => $style->line_id,
			'productBrief' => json_decode($style->product_brief),
			'customerStyleCode' => $style->customer_style_code,
			'techpack' => $techpack,
			'tna' => $tna,
            'order' => $order,
            // 'relatedSamples' => $relatedSamples,
            'sampleContainer' => $sampleContainer,
			'flat' => $style->flat,
			'development' => $development,
			'production' => $production,
			'shipped' => $shipped,
			'review' => $review,
			'createdAt' => $style->created_at->toDateTimeString(),
			'updatedAt' => $style->updated_at->toDateTimeString(),
           	'archivedAt' => is_null($style->archived_at)? NULL : $style->archived_at->toDateTimeString(),
            'completedAt' => is_null($style->completed_at)? NULL : $style->completed_at->toDateTimeString(),
           	'isArchived' => !is_null($style->archived_at)
		];
		return $style;
	}

}
