<?php

namespace Platform\App\Console;

use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Console\SeConsole;
use Platform\TNA\Commands\TNAPublishActionCommand;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Helpers\TNAPublisher;
use Platform\App\RuleCommanding\DefaultRuleBus;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class TNAPublishCommand extends SeConsole
{
    use DispatchesJobs;
    /**
     * @var Platform\TNA\Repositories\Contracts\TNARepository
     */
    protected $tnaRepository;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var Platform\TNA\Helpers\TNAPublisher
     */
    protected $tnaPublisher;

    /**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    /**
     * Create a new command instance.
     *
     * @param DefaultRuleBus     $defaultRuleBus
     * @return void
     */
    public function __construct(DefaultCommandBus $commandBus,
                                TNAPublisher $tnaPublisher,
                                TNARepository $tnaRepository,
                                DefaultRuleBus $defaultRuleBus)
    {
        $this->signature = 'tnaAction:published';
        $this->description = 'Take action for published TNA.';

        $this->commandBus = $commandBus;
        $this->tnaRepository = $tnaRepository;
        $this->tnaPublisher = $tnaPublisher;
        $this->defaultRuleBus = $defaultRuleBus;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tnaList = $this->tnaRepository->getTNAToBePublished();
        foreach ($tnaList as $key => $tna) {
            try{
                $this->tnaPublisher->publish($tna);
                $tna->is_publishing = false;
                $tna->save();
                 if(!is_null($tna->line())) {
                    $job = (new DefaultRuleBusJob($tna, $tna->representor, 'TnaPublish'));
                    $this->dispatch($job);
                 }
            } catch(\Exception $e) {
            }
        }
    }
}
