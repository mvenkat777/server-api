<?php

namespace Platform\SampleSubmission\Transformers;

use App\SampleSubmissionAttachment;
use League\Fractal\TransformerAbstract;

class SampleSubmissionAttachmentTransformer extends TransformerAbstract
{
    public function transform(SampleSubmissionAttachment $attachment)
    {
        return [
            'id' => (string) $attachment->id,
            'file' => $attachment->file,
            'uploadedBy' => $attachment->uploaded_by,
            'createdAt' => $attachment->created_at->toDateTimeString(),
        ];
    }
}
