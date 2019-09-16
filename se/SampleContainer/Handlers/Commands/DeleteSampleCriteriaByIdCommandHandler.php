<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaRepository;

class DeleteSampleCriteriaByIdCommandHandler implements CommandHandler
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
        $sampleContainer = $this->sampleCriteria->getBySampleIdAndCriteriaId(
            $command->sampleId,
            $command->criteriaId
        );

        if ($sampleContainer) {
            return $sampleContainer->delete();
        }
        throw new SeException("Criteria not found.", 404);
    }
}