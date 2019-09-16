<?php namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Activity\Recorders\TechpackActivityRecorder;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Commands\AddColorwayCommand;
use Platform\Techpacks\Commands\AddCutTicketCommand;
use Platform\Techpacks\Commands\AddCutTicketNoteCommand;
use Platform\Techpacks\Commands\UpdateSamplePOMFieldCommand;
use Platform\Techpacks\Commands\UpdateTechpackCommand;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;
/**
 * Class UpdateTechpackHandler
 * @package App\Handlers\Commands\Techpack
 */
class UpdateTechpackCommandHandler implements CommandHandler
{ 
    use DispatchesJobs;
    /**
     * @var TechpackRepository
     */
    protected $techpack;

    /**
     * @param TechpackRepository $techpack
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(TechpackRepository $techpack, DefaultCommandBus $commandBus, DefaultRuleBus $defaultRuleBus)
    {
        $this->techpack = $techpack;
        $this->commandBus = $commandBus;
        $this->defaultRuleBus = $defaultRuleBus;
    }


    /**
     * @param UpdateTechpack $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        $this->commandBus->execute(new AddColorwayCommand($command->id, $command));
        if (!empty($command->cut_tickets)) {
            $this->commandBus->execute(new AddCutTicketCommand($command->id, $command->bill_of_materials, $command->cut_tickets['list']));
            if (isset($command->cut_tickets['note']) || isset($command->cut_tickets['image'])) {
                $this->commandBus->execute(
                    new AddCutTicketNoteCommand(
                        $command->id,
                        $command->cut_tickets
                    )
                );
            }
        }
        $preUpdate = $this->techpack->find($command->id);
        $techpack = $this->techpack->updateTechpack($command);
        $style = \App\Style::where('techpack_id', $techpack->id)->first();
        if ($style) {
            $style->timestamps = false;
            $style->flat = $techpack->image;
            $style->save();
        }

        if (!empty($command->changeLogs)) {
            $this->recordChangeLog($command->changeLogs, $techpack);
        }

        $this->postUpdateActions($techpack);

        return $techpack;
    }

    public function recordChangeLog($changeLogs, $techpack)
    {
        if(isset($changeLogs['data'])){
            foreach ($changeLogs['data'] as $key => $value) {
                if($value['fieldName'] == 'stage'){
                    $techpack->updatedStageValue = $value['updatedValue'];
                    // $this->defaultRuleBus->execute($techpack->replicate(), \Auth::user(), 'UpdateTechpackStage');
                    $job = (new DefaultRuleBusJob($techpack, \Auth::user(), 'UpdateTechpackStage'));
                     $this->dispatch($job);
                    break;
                }
            }
        }
        $techpackActivityRecorder = new TechpackActivityRecorder();
        $techpackActivityRecorder->record($changeLogs);
    }

    /**
     * Perform the post update actions for a techpack
     * @param  object $techpack
     * @return string
     */
    private function postUpdateActions($techpack)
    {
        $this->commandBus->execute(new UpdateSamplePOMFieldCommand($techpack));
    }
}
