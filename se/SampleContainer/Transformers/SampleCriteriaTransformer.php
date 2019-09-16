<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\SampleContainer\Transformers\SampleCriteriaAttachmentTransformer;
use Platform\SampleContainer\Transformers\SampleCriteriaCommentTransformer;

class SampleCriteriaTransformer extends TransformerAbstract
{
    /**
     * Construct the SampleCriteriaTransformer
     */
	public function __construct()
	{
		$this->manager = new Manager();
	}

    /**
     * Transform sample criteria for api
     * @param  object $SampleCriteria
     * @return array
     */
	public function transform($sampleCriteria)
	{
		$data = [
            'id' => $sampleCriteria->id,
            'sampleId' => $sampleCriteria->sample_id,
            'criteria' => $sampleCriteria->criteria,
            'description' => $sampleCriteria->description,
            'note' => $sampleCriteria->note,
            'createdAt' => $sampleCriteria->created_at->toDateTimeString(),
            'updatedAt' => $sampleCriteria->updated_at->toDateTimeString(),
        ];

        if ($sampleCriteria->attachments->count() > 0) {
            $attachments = new Collection($sampleCriteria->attachments, new SampleCriteriaAttachmentTransformer());
            $attachments = $this->manager->createData($attachments)->toArray();
            $data['attachments'] = $attachments['data'];
        } else {
            $data['attachments'] = [];
        }

        if ($sampleCriteria->comments->count() > 0) {
            $comments = new Collection($sampleCriteria->comments, new SampleCriteriaCommentTransformer());
            $comments = $this->manager->createData($comments)->toArray();
            $data['comments'] = $comments['data'];
        } else {
            $data['comments'] = [];
        }

        return $data;
	}

}
