<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaRepository;

class AddNewSampleCriteriaCommandHandler implements CommandHandler
{
    /**
     * The sample criteria repository instance
     * @var SampleCriteriaRepository
     */
    private $sampleCriteria;

    /**
     * Constructing the handler
     * @param SampleCriteriaRepository $sampleCriteria
     */
	public function __construct(SampleCriteriaRepository $sampleCriteria)
	{
        $this->sampleCriteria = $sampleCriteria;
	}

    /**
     * Handle it. The AddNewSampleCriteriaCommand
     * @param  AddNewSampleCriteriaCommandHandler $command
     * @return mixed
     */
	public function handle($command)
	{
        $this->doesCriteriaExist($command->sampleId, $command->criteria);
        $this->isAllowedCriteria($command->criteria);
        return $this->sampleCriteria->addCriteria($command);
	}

    /**
     * Check if the criteria is allowed or not
     * @param  string  $criteria
     * @return boolean
     */
    public function isAllowedCriteria($criteria)
    {
        $allowedCriterias = [
            'fabrics', 'trims', 'fit', 'construction', 'pom and grading',
        ];
        if (in_array(strtolower($criteria), $allowedCriterias)) {
            return true;
        }
        throw new SeException("Invalid criteria.", 422);
    }

    /**
     * Check if criteria is already there for the sample
     * @param  string $sampleId
     * @param  string $criteria
     * @return boolean
     */
    public function doesCriteriaExist($sampleId, $criteria)
    {
        if ($this->sampleCriteria->getBySampleIdAndCriteria($sampleId, $criteria)) {
            throw new SeException("Criteria already exists.", 409);
        }
        return true;
    }

}
