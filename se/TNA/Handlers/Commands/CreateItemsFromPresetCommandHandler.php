<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Helpers\TNAHelper;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\TNA\Commands\CreateTNAItemCommand;
use Platform\TNA\Commands\SyncCommand;

class CreateItemsFromPresetCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @param DefaultCommandBus $commandBus    
	 * @param TNARepository     $tnaRepository 
	 */
	public function __construct(
		DefaultCommandBus $commandBus,
		TNARepository $tnaRepository
	) {
		$this->commandBus = $commandBus;
		$this->tnaRepository = $tnaRepository;
	}

    /**
     * @param Command Object $command
     * @return 
     */
	public function handle($command)
	{
		$presetItems = \Platform\TNA\Models\TNAItemPreset::all();
        $itemDepartments = \Platform\TNA\Models\TNAItemDepartment::lists('department', 'id')->toArray();
        $tna = $this->tnaRepository->getById($command->tnaId);

        if(is_null($tna)){
            return;
        }

        \DB::beginTransaction();
        $this->createItems($presetItems, $tna, $command->departments, $itemDepartments);
        \DB::commit();
	}

    /**
     * Create default items for tna
     *
     * @param collection $presetItems
     * @param object $tna
     * @param array $departments
     * @param collection $itemDepartments
     * @return
     */
    private function createItems($presetItems, $tna, $departments, $itemDepartments)
    {
        $previousPlannedDate = $tna->start_date;
        $itemsOrder = [];
        $milestone = null;

		foreach($presetItems as $key => $presetItem){ 
			$data = TNAHelper::convertForItemCommand($presetItem, $tna, $departments, $itemDepartments);

            $taskDays = 0;
            if($key !==  count($presetItems) - 1) {
                for($i = $key+1;$i < count($presetItems);$i++) {
                    if($presetItems[$i]->is_milestone) {
                        $taskDays = $presetItem->task_days;
                        break;
                    }
                    $taskDays = $taskDays + $presetItems[$i]->task_days;
                }
            } else {
                $taskDays = $presetItem->task_days;
            }

            $data['plannedDate'] = TNAHelper::addDayToDate($previousPlannedDate, $taskDays);

            // First item in preset should always be a milestone
            if(!$presetItem->is_milestone){
                $data['dependorId'] = $milestone->id;
            }

			$tnaItem = $this->commandBus->execute(new CreateTNAItemCommand($data, $tna->id, $tna));
            //$itemsOrder[] = (new TNAItemTransformer)->transform($tnaItem);
            $previousPlannedDate = $tnaItem->planned_date;

            if($presetItem->is_milestone){
                $milestone = $tnaItem;
            }
		}
        //$tna->itemsOrder = json_encode($itemsOrder);
        $tna->is_creating_preset = false;
        $tna->save();
    }

    /**
     * Calculate and save items order of tna
     *
     * @param object $tna
     * @return 
     */
    private function calculateAndSaveItemsOrder($tna)
    {
        $itemsOrder = (new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tna->id);
		$itemsOrder = $this->commandBus->execute(new SyncCommand($itemsOrder, $tna->id));
		$tna->items_order = json_encode($itemsOrder);
        $tna->is_creating_preset = false;
		if(!$tna->save()){
			throw new SeException('Unable to create TNA items preset', 500, 50000);
		}
    }

}
