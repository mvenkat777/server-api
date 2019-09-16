<?php

namespace Platform\SampleSubmission\Repositories\Eloquent;

use App\SampleSubmissionComment;
use Illuminate\Support\Facades\Auth;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCommentRepository;

class EloquentSampleSubmissionCommentRepository extends Repository implements SampleSubmissionCommentRepository
{

    public function model()
    {
        return 'App\SampleSubmissionComment';
    }

    /**
     * Add a new comment to the sample submission csategory
     * @param string $sampleId
     * @param string $categoryId string
     * @param array $data
     */
    public function addComment($sampleId, $categoryId, $data)
    {
        $user = [
            "displayName" => Auth::user()->display_name,
            "email" => Auth::user()->email,
        ];

        $data = [
            'sample_submission_id' => $sampleId,
            'sample_submission_categories_id' => $categoryId,
            'comment' => $data['comment'],
            'commented_by' => $user,
        ];

        return $this->create($data);
    }

    /**
     * Delete a sample submission comment
     * @param  string $commentId
     * @return mixed
     */
    public function deleteComment($commentId)
    {
        $comment = $this->find($commentId);

        if (!$comment) {
            throw new SeException('We were not able to find a comment with that id.', 404);
        }

        return $comment->delete();
    }
}
