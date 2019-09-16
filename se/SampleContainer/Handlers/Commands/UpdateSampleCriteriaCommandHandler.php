<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaRepository;

class UpdateSampleCriteriaCommandHandler implements CommandHandler
{
    /**
     * The sample criteria repositiry
     * @var string
     */
    private $sampleCriteria;

    /**
     * Construct the handler
     * @param SampleCriteriaRepository $sample
     */
    public function __construct(SampleCriteriaRepository $sampleCriteria)
    {
        $this->sampleCriteria = $sampleCriteria;
    }

    /**
     * Handle the UpdateSampleCriteriaCommand
     * @param  UpdateSampleCriteriaCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        return $this->sampleCriteria->updateCriteria($command);
    }
}