<?php

namespace Platform\SampleSubmission\Repositories\Contracts;

interface SampleSubmissionRepository
{
    public function model();

    /**
     * Submit a new sample
     * @param  array $data
     * @return mixed
     */
    public function submitSample($data);

    /**
     * Delete a sample submission
     * @param  string $sampleId
     * @return mixed
     */
    public function deleteSample($sampleId);

    /**
     * Filter based on input
     * @param  array $filterValues
     * @return mixed
     */
    public function filterSamples($filterValues);
}
