<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCommentRepository;
use Platform\SampleSubmission\Transformers\SampleSubmissionCommentTransformer;

class SampleSubmissionCommentController extends ApiController
{
    /**
     * @var SampleSubmissionCommentRepository
     */
    protected $comment;

    /**
     * @param SampleSubmissionCommentRepository     $comment
     */
    public function __construct(SampleSubmissionCommentRepository $comment)
    {
        parent::__construct(new Manager());

        $this->comment = $comment;
    }

    /**
     * Add a new comment on a sample submission category
     * @param string $sampleId
     * @param string $categoryId
     */
    public function store($sampleId, $categoryId, Request $request)
    {
        $comment = $this->comment->addComment($sampleId, $categoryId, $request->all());

        if ($comment) {
            return $this->respondWithNewItem(
                $comment,
                new SampleSubmissionCommentTransformer,
                'SampleSubmissionComment'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to add the comment. Please try again.');
    }

    /**
     * Get all comments for a sample submission category
     * @param  string $sampleId
     * @param  string $categoryId
     * @return mixed
     */
    public function index($sampleId, $categoryId)
    {
        $comments = $this->comment->getComments($sampleId, $categoryId);

        if ($comments) {
            return $this->respondWithCollection(
                $comments,
                new SampleSubmissionCommentTransformer,
                'SampleSubmissionComment'
            );
        }

        return $this->setStatusCode(404)
                    ->respondWithError('No comments were found for this category.');
    }

    /**
     * Delete a sample submission comment
     * @param  string $sampleId
     * @param  string $categoryId
     * @param  string $commentId
     * @return mixed
     */
    public function destroy($sampleId, $categoryId, $commentId)
    {
        $deleted = $this->comment->deleteComment($commentId);

        if ($deleted) {
            return $this->respondOk('Comment deleted successfully.');
        }

        return $this->setStatusCode(500)
                    ->respondWithError(
                        'Something went wrong. We were not able to delete the comment. Please try again.'
                    );
    }
}
