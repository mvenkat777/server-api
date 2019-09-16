<?php

namespace Platform\SampleSubmission\Transformers;

use App\SampleSubmissionComment;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class SampleSubmissionCommentTransformer extends TransformerAbstract
{
    public function transform(SampleSubmissionComment $comment)
    {
        $fractal = new Manager();

        $response = [
            'id' => (string) $comment->id,
            'comment' => $comment->comment,
            'commentedBy' => $comment->commented_by,
            'createdAt' => $comment->created_at->toDateTimeString(),
        ];

        return $response;
    }
}
