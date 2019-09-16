<?php

namespace Platform\SampleSubmission\Repositories\Contracts;

interface SampleSubmissionCategoryRepository
{
    public function model();

    /**
     * Add a new category to sample submission
     * @param array $data
     */
    public function addCategory($sampleId, $data);
}
