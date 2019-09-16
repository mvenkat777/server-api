<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;


class AddNewSampleCommandHandler implements CommandHandler
{  
    use DispatchesJobs;
    /**
     * The sample repositiry
     * @var string
     */
    private $sample;

    /**
     * The sample container repositiry
     * @var string
     */
    private $sampleContainer;

    /**
     * Construct the handler
     * @param SampleRepository $sample
     */
	public function __construct(
        SampleRepository $sample,
        DefaultRuleBus $defaultRuleBus,
        SampleContainerRepository $sampleContainer)
	{
        $this->sample = $sample;
        $this->sampleContainer = $sampleContainer;
        $this->defaultRuleBus = $defaultRuleBus;
	}

    /**
     * Handle it. Oh, the AddNewSampleCommand
     * @param  AddNewSampleCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        $sampleContainer = $this->sampleContainer->find($command->sampleContainerId);
        $command->pom = $this->formatPomForSample($sampleContainer->techpack->poms);
        $sample = $this->sample->addSample($command);
        $sampleContainer->sample = $sample;
        if(!is_null($sampleContainer->style)){
            // $this->defaultRuleBus->execute($sampleContainer, \Auth::user(), 'createNewLineSample');
            $job = (new DefaultRuleBusJob($sampleContainer, \Auth::user(), 'CreateNewLineSample'));
         $this->dispatch($job);

        }
        return $sample;
	}

    /**
     * Format the techpack pom for sample
     * @param  object $poms
     * @return object
     */
    public function formatPomForSample($poms)
    {
        if (!isset($poms->pom) && empty($poms->pom)) {
            return $poms;
        }

        unset($poms->sizeRange);
        foreach ($poms->pom as $pom) {
            unset($pom->values);
            $pom->requested = '';
            $pom->actual = '';
            $pom->deviation = '';
            $pom->comment = '';
            $pom->revisions = '';
        }

        return $poms;
    }

}