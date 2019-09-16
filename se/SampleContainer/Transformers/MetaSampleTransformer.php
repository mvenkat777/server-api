<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaSampleTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    /**
     * Transform the sample for api
     * @param  object $sample
     * @return mixed
     */
    public function transform($sample)
    {
        return [
            'id' => $sample->id,
            'title' => $sample->title,
            'createdAt' => $sample->created_at->toDateTimeString(),
            'updatedAt' => $sample->updated_at->toDateTimeString(),
            'archivedAt' => is_null($sample->archived_at)? NULL : $sample->archived_at->toDateTimeString(),
            'completedAt' => is_null($sample->completed_at)? NULL : $sample->completed_at->toDateTimeString(),
        ];
    }
}