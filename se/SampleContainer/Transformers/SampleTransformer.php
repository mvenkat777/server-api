<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\SampleContainer\Transformers\SampleCriteriaTransformer;

class SampleTransformer extends TransformerAbstract
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
        $data = [
            'id' => $sample->id,
            'sampleContainerId' => $sample->sample_container_id,
            'title' => $sample->title,
            'type' => $sample->type,
            'author' => [
                'id' => $sample->author->id,
                'displayName' => $sample->author->display_name,
                'email' => $sample->author->email,
                'lastLoginLocation' => $sample->author->last_login_location     
            ],
            'image' => $sample->image,
            'sentDate' => $sample->sent_date,
            'receivedDate' => $sample->received_date,
            'weightOrQuality' => $sample->weight_or_quality,
            'fabricOrContent' => $sample->fabric_or_content,
            'pom' => $sample->pom,
            'actionForward' => $sample->action_forward,
            'createdAt' => $sample->created_at->toDateTimeString(),
            'updatedAt' => $sample->updated_at->toDateTimeString(),
            'archivedAt' => is_null($sample->archived_at)? NULL : $sample->archived_at->toDateTimeString(),
            'completedAt' => is_null($sample->completed_at)? NULL : $sample->completed_at->toDateTimeString(),
        ];

        if (!is_null($sample->vendor)) {
            $data['vendor'] = [
                'id' => $sample->vendor->id,
                'name' => $sample->vendor->name,
                'code' => $sample->vendor->code,
            ];
        } else {
            $data['vendor'] = null;
        }

        if ($sample->criterias->count() > 0) {
            $criterias = new Collection($sample->criterias, new SampleCriteriaTransformer());
            $criterias = $this->manager->createData($criterias)->toArray();
            $data['criterias'] = $criterias['data'];
        } else {
            $data['criterias'] = [];
        }

        return $data;
	}
}