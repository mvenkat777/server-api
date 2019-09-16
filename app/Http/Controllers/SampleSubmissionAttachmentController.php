<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository;
use Platform\SampleSubmission\Transformers\SampleSubmissionAttachmentTransformer;

class SampleSubmissionAttachmentController extends ApiController
{
    /**
     * @var Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository
     */
    protected $attachment;

    /**
     * @param SampleSubmissionAttachmentRepository  $attachment
     */
    public function __construct(SampleSubmissionAttachmentRepository $attachment)
    {
        parent::__construct(new Manager());

        $this->attachment = $attachment;
    }

    /**
     * Add a new comment on a sample submission category
     * @param string $sampleId
     * @param string $categoryId
     */
    public function store($sampleId, $categoryId, Request $request)
    {
        $attachment = $this->attachment->addAttachment($sampleId, $categoryId, $request->all());

        if ($attachment) {
            return $this->respondWithNewItem(
                $attachment,
                new SampleSubmissionAttachmentTransformer,
                'SampleSubmissionAttachment'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to add the attachment. Please try again.');
    }

    /**
     * Delete an attachment
     * @param  string $sampleId
     * @param  string $categoryId
     * @param  string $attachmentId
     * @return mixed
     */
    public function destroy($sampleId, $categoryId, $attachmentId)
    {
        $deleted = $this->attachment->deleteAttachment($attachmentId);

        if ($deleted) {
            return $this->respondOk('Attachment Successfully deleted.');
        }

        return $this->setStatusCode(500)
                    ->respondWithError(
                        'Something went wrong. We were not able to delete the attachment. Please try again.'
                    );
    }
}
