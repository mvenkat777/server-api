<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Activity\Recorders\SampleActivityRecorder;
use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;

class UpdateSampleCommandHandler implements CommandHandler
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
     * Handle it. Oh, the AddNewSampleCommand
     * @param  AddNewSampleCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        if (!empty($command->changeLogs)) {
            $this->recordChangeLog($command->changeLogs);
        }

        return $this->sample->updateSample($command);
    }

    public function recordChangeLog($changeLogs)
    {
        $sampleActivityRecorder = new SampleActivityRecorder();
        $sampleActivityRecorder->record($changeLogs);
    }
}