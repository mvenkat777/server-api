<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaRepository;

class GetSampleCriteriaByIdCommandHandler implements CommandHandler
{
    /**
     * The sample criteria repositiry
     * @var string
     */
    private $sampleCriteria;

    /**
     * Construct the handler
     * @param SampleCriteriaRepository $sampleCriteria
     */
    public function __construct(SampleCriteriaRepository $sampleCriteria)
    {
        $this->sampleCriteria = $sampleCriteria;
    }

    /**
     * Handle the GetSampleCriteriaByIdCommand
     * @param  GetSampleCriteriaByIdCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $sampleCriteria = $this->sampleCriteria->getBySampleIdAndCriteriaId(
            $command->sampleId,
            $command->criteriaId
        );

        if ($sampleCriteria) {
            return $this->sampleCriteria->getByIdWithRelations(
                $sampleCriteria->id
            );
        }
        throw new SeException("Criteria not found for this sample.", 404);
    }
}