<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;

class ListSampleContainersCommandHandler implements CommandHandler
{
    /**
     * The sample container repository
     * @var SampleContainerRepository
     */
    private $sampleContainer;

    /**
     * Constructing the ListSampleContainersCommand handler
     * @param SampleContainerRepository $sampleContainer
     */
	public function __construct(SampleContainerRepository $sampleContainer)
	{
        $this->sampleContainer = $sampleContainer;
	}

    /**
     * Handle it. Oh, the command. Oh, the ListSampleContainersCommand
     * @param  ListSampleContainersCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        return $this->sampleContainer->filterSampleContainer($command->data);
	}

}