<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\SampleContainer\Transformers\MetaSampleTransformer;

class SampleContainerTransformer extends TransformerAbstract
{
	public function __construct()
	{
		$this->manager = new Manager();
	}

    /**
     * Transform SampleContainer for api
     * @param  object $sampleContainer
     * @return array
     */
	public function transform($sampleContainer)
	{
		$data = [
            'id' => $sampleContainer->id,
            'techpack' => [
                'id' => $sampleContainer->techpack->id,
                'name' => $sampleContainer->techpack->name,
                'flatImage' => $sampleContainer->techpack->image,
                'styleCode' => $sampleContainer->techpack->style_code,
                'archivedAt' => $sampleContainer->techpack->archived_at
            ],
            'customer' => [
                'id' => $sampleContainer->customer->id,
                'name' => $sampleContainer->customer->name,
                'code' => $sampleContainer->customer->code,
                'archivedAt' => $sampleContainer->customer->archived_at
            ],
            'createdAt' => $sampleContainer->created_at->toDateTimeString(),
            'updatedAt' => $sampleContainer->updated_at->toDateTimeString(),
            'archivedAt' => is_null($sampleContainer->archived_at)? NULL : $sampleContainer->archived_at,        
            'completedAt' => is_null($sampleContainer->completed_at)? NULL : $sampleContainer->completed_at,        
        ];

        if ($sampleContainer->samples->count() > 0) {
            $samples = new Collection($sampleContainer->samples, new MetaSampleTransformer());
            $samples = $this->manager->createData($samples)->toArray();
            $data['samples'] = $samples['data'];
        } else {
            $data['samples'] = [];
        }

        if (!is_null($sampleContainer->style) && ($sampleContainer->style->count() > 0)) {
            if (!is_null($sampleContainer->style->line) && ($sampleContainer->style->line->count() > 0)) {
                $data['line'] = [
                    'id' => $sampleContainer->style->line->id,
                    'name' => $sampleContainer->style->line->name,
                    'archivedAt' => $sampleContainer->style->line->archived_at
                ];
            } else {
                $data['line'] = [];
            }

            if(!is_null($sampleContainer->style->tna)) {
                $data['tna'] = [
                    'tnaId' => $sampleContainer->style->tna->id,
                    'title' => $sampleContainer->style->tna->title,
                    'archivedAt' => $sampleContainer->style->tna->archived_at
                ];
            } else {
                $data['tna'] = null;
            }
        } else {
            $data['line'] = [];
            $data['tna'] = null;
        }

        return $data;
	}
}
