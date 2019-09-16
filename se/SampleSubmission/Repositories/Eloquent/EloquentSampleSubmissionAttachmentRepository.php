<?php

namespace Platform\SampleSubmission\Repositories\Eloquent;

use App\SampleSubmissionComment;
use Illuminate\Support\Facades\Auth;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository;

class EloquentSampleSubmissionAttachmentRepository extends Repository implements SampleSubmissionAttachmentRepository
{

    public function model()
    {
        return 'App\SampleSubmissionAttachment';
    }

    /**
     * Add a new attachment
     * @param string $sampleId
     * @param string $categoryId
     * @param string $data
     */
    public function addAttachment($sampleId, $categoryId, $data)
    {
        $user = [
            "displayName" => Auth::user()->display_name,
            "email" => Auth::user()->email,
        ];

        $data = [
            'sample_submission_id' => $sampleId,
            'sample_submission_categories_id' => $categoryId,
            'file' => $data['file'],
            'uploaded_by' => $user,
        ];

        return $this->create($data);
    }

    /**
     * Delete an attachment
     * @param  string $attachmentId
     * @return string
     */
    public function deleteAttachment($attachmentId)
    {
        $attachment = $this->find($attachmentId);

        if (!$attachment) {
            throw new SeException('An attachment with that id not found.', 404);
        }

        return $attachment->delete();
    }
}
