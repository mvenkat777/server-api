<?php

namespace Platform\SampleSubmission\Repositories\Contracts;

interface SampleSubmissionCommentRepository
{
    public function model();

    /**
     * Add a new comment to the sample submission csategory
     * @param string $sampleId
     * @param string $categoryId string
     * @param array $data
     */
    public function addComment($sampleId, $categoryId, $data);

    /**
     * Delete a sample submission comment
     * @param  string $commentId
     * @return mixed
     */
    public function deleteComment($commentId);
}
