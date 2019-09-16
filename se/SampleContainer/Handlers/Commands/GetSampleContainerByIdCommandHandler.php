<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;

class GetSampleContainerByIdCommandHandler implements CommandHandler
{
    /**
     * The sample container repositiry
     * @var string
     */
    private $sampleContainer;

    /**
     * Construct the handler
     * @param SampleContainerRepository $sample
     */
    public function __construct(SampleContainerRepository $sampleContainer)
    {
        $this->sampleContainer = $sampleContainer;
    }

    /**
     * Handle the GetSampleByIdCommand
     * @param  GetSampleByIdCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        return $this->sampleContainer->getByIdWithRelations(
            $command->sampleContainerId
        );
    }
}