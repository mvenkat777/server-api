<?php

namespace Platform\SampleContainer\Repositories\Contracts;

interface SampleCriteriaRepository
{
	public function model();

    /**
     * Adds a new criteria
     * @param AddNewSampleCriteriaCommand $command
     */
    public function addCriteria($command);

    /**
     * Update a criteria
     * @param AddNewSampleCriteriaCommand $command
     */
    public function updateCriteria($command);

    /**
     * Get a SampleCriteria by id with its relations
     * @param  string $criteriaId
     * @return mixed
     */
    public function getByIdWithRelations($criteriaId);

    /**
     * Get a criteria by sample id and criteria id
     * @param  string $sampleId
     * @param  string $criteriaId
     * @return mixed
     */
    public function getBySampleIdAndCriteriaId($sampleId, $criteriaId);
}