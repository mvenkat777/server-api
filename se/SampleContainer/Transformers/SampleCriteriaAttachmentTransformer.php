<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class SampleCriteriaAttachmentTransformer extends TransformerAbstract
{
	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($criteriaAttachment)
	{
		return [
            'id' => $criteriaAttachment->id,
            'criteriaId' => $criteriaAttachment->sample_criteria_id,
            'file' => $criteriaAttachment->file,
            'uploader' => [
                'id' => $criteriaAttachment->uploader->id,
                'displayName' => $criteriaAttachment->uploader->display_name,
                'email' => $criteriaAttachment->uploader->email,
                'lastLoginLocation' => $criteriaAttachment->uploader->last_login_location     
            ],
            'createdAt' => $criteriaAttachment->created_at->toDateTimeString(),
            'updatedAt' => $criteriaAttachment->updated_at->toDateTimeString(),
        ];
	}
}