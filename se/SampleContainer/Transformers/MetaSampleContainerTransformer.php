<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaSampleContainerTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    /**
     * Transform SampleContainer for list api
     * @param  object $sampleContainer
     * @return array
     */
    public function transform($sampleContainer)
    {
        return [
            'id' => $sampleContainer->id,
            'name' => $sampleContainer->techpack->name,
            'styleCode' => $sampleContainer->techpack->style_code,
            'customerName' => $sampleContainer->customer->name,
            'createdAt' => $sampleContainer->created_at->toDateTimeString(),
            'updatedAt' => $sampleContainer->updated_at->toDateTimeString(),
            'archivedAt' => is_null($sampleContainer->archived_at)? NULL : $sampleContainer->archived_at->toDateTimeString(),
            'completedAt' => is_null($sampleContainer->completed_at)? NULL : $sampleContainer->completed_at->toDateTimeString(),
        ];
    }
}