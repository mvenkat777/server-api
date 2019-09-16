<?php

namespace Platform\SampleContainer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaRepository;
use App\SampleCriteria;

class EloquentSampleCriteriaRepository extends Repository implements SampleCriteriaRepository
{
    /**
     * Returns the SampleCriteria model namespace
     * @return string
     */
	public function model(){
		return 'App\SampleCriteria';
	}

    /**
     * Adds a new criteria
     * @param AddNewSampleCriteriaCommand $command
     */
    public function addCriteria($command)
    {
        $data = [
            'id' => $this->generateUUID(),
            'sample_id' => $command->sampleId,
            'criteria' => strtolower($command->criteria),
            'description' => $command->description,
            'note' => $command->note,
        ];

        $criteria = $this->model->create($data);
        return $this->getByIdWithRelations($criteria->id);
    }

    /**
     * Update a criteria
     * @param AddNewSampleCriteriaCommand $command
     */
    public function updateCriteria($command)
    {
        $sampleCriteria = $this->getBySampleIdAndCriteriaId(
            $command->sampleId,
            $command->criteriaId
        );

        if ($sampleCriteria) {
            $data = [
                'description' => $command->description,
                'note' => $command->note,
            ];

            $sampleCriteria->update($data);
            return $this->getByIdWithRelations($sampleCriteria->id);
        }

        throw new SeException("Criteria not found for this sample.", 404);
    }

    /**
     * Get a SampleCriteria by id with its relations
     * @param  string $criteriaId
     * @return mixed
     */
    public function getByIdWithRelations($criteriaId)
    {
        return $this->model->with(['attachments'])
                           ->where('id', $criteriaId)
                           ->first();
    }

    /**
     * Get a criteria by sample id and criteria id
     * @param  string $sampleId
     * @param  string $criteriaId
     * @return mixed
     */
    public function getBySampleIdAndCriteriaId($sampleId, $criteriaId)
    {
        return $this->model->where('sample_id', $sampleId)
                           ->where('id', $criteriaId)
                           ->first();
    }

    /**
     * Get a criteria by sample id and criteria
     * @param  string $sampleId
     * @param  string $criteria
     * @return mixed
     */
    public function getBySampleIdAndCriteria($sampleId, $criteria)
    {
        return $this->model->where('sample_id', $sampleId)
                           ->where('criteria', strtolower($criteria))
                           ->first();
    }

}
