<?php

namespace Platform\SampleContainer\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class SampleCriteriaCommentTransformer extends TransformerAbstract
{
    public function transform($criteriaComment)
    {
        return [
            'id' => $criteriaComment->id,
            'criteriaId' => $criteriaComment->sample_criteria_id,
            'comment' => $criteriaComment->comment,
            'commenter' => [
                'id' => $criteriaComment->commenter->id,
                'displayName' => $criteriaComment->commenter->display_name,
                'email' => $criteriaComment->commenter->email,
                'lastLoginLocation' => $criteriaComment->commenter->last_login_location     
            ],
            'createdAt' => $criteriaComment->created_at->toDateTimeString(),
            'updatedAt' => $criteriaComment->updated_at->toDateTimeString(),
        ];
    }
}
