<?php

namespace Platform\SampleSubmission\Transformers;

use App\SampleSubmissionCategory;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class SampleSubmissionCategoryTransformer extends TransformerAbstract
{
    public function transform(SampleSubmissionCategory $category)
    {
        $fractal = new Manager();

        $comments = new Collection($category['comments'], new SampleSubmissionCommentTransformer());
        $comments = $fractal->createData($comments)->toArray()['data'];

        $attachments = new Collection($category['attachments'], new SampleSubmissionAttachmentTransformer());
        $attachments = $fractal->createData($attachments)->toArray()['data'];

        return [
            'id' => (string) $category->id,
            'sampleId' => (string) $category->sample_submission_id,
            'name' => $category->name,
            'content' => $category->content,
            'comments' => $comments,
            'attachments' => $attachments,
            'createdAt' => $category->created_at->toDateTimeString(),
            'updatedAt' => $category->updated_at->toDateTimeString(),
        ];
    }
}
