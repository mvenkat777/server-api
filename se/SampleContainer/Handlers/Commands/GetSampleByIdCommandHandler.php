<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;

class GetSampleByIdCommandHandler implements CommandHandler
{
    /**
     * The sample repositiry
     * @var string
     */
    private $sample;

    /**
     * Construct the handler
     * @param SampleRepository $sample
     */
    public function __construct(SampleRepository $sample)
    {
        $this->sample = $sample;
    }

    /**
     * Handle the GetSampleByIdCommand
     * @param  GetSampleByIdCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        $sample = $this->sample->getBySampleContainerIdAndSampleId(
            $command->sampleContainerId,
            $command->sampleId
        );

        if ($sample) {
            return $this->sample->getByIdWithRelations($sample->id);
        }
        throw new SeException("Sample not found in container.", 404);
	}
}