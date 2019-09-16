<?php

namespace Platform\TNA\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Commands\SyncCommand;
use Platform\TNA\Handlers\Console\SyncByPlannedDate;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Transformers\TNAItemTransformer;

class UpdateTNAItemCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepo;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @param TNAItemRepository   $tnaItemRepo         
	 * @param TNAHelper           $tnaHelper           
	 * @param DefaultCommandBus   $commandBus          
	 */
	public function __construct(TNAItemRepository $tnaItemRepo, 
								DefaultCommandBus $commandBus)
	{
		$this->tnaItemRepo = $tnaItemRepo;
		$this->commandBus = $commandBus;
	}

	/**
	 * @param  UpdateTNAItemCommand $command 
	 * @return TNAItem          
	 */
	public function handle($command)
	{
		//$command->visibility = TNAHelper::getVisibilityIdArray($command->visibility);

		\DB::beginTransaction();

        //$command->projectedDate = ($command->tnaItem->is_priority_task) ? $command->plannedDate : $command->projectedDate;
        //$command->delta = 0;
        //$command->taskDays = $this->calculateTaskDays($command->tna, $command->tnaItem, $command->plannedDate);
		$tnaItem = $this->tnaItemRepo->updateItem((array)$command);

		$tnaItem->itemsOrder = $this->updateItemsOrder($tnaItem, $command->tnaItem, $command->tna);
        $tnaItem->tna  = (new TNAProjectedDateCalculator)->calculate($tnaItem->tna, $tnaItem->itemsOrder);
		
		//\Queue::push($this->tnaItemRepo->saveTask($tnaItem));
		$this->tnaItemRepo->saveTask($tnaItem);
		\DB::commit();

		return $tnaItem;
	}

    /**
     * Update itemsorder json in tna
     *
     * @param object $tnaItem
     * @param object $prevTnaItem
     * @param object $tna
     * @return jsonarray
     */
    private function updateItemsOrder($tnaItem, $prevTnaItem, $tna)
    {
        /*
        if($tnaItem->planned_date !== $prevTnaItem->planned_date) {
            //$tnaItem->task_days = $this->getTaskDays($tnaItem, $prevTnaItem);
           //$itemsOrder = (new SyncByPlannedDate($tna))->sync($itemsOrder);
        } 
         */

        $itemsOrder = $this->updateOneItemOrder($tnaItem);

        /*
        if($tnaItem->planned_date !== $prevTnaItem->planned_date 
            || $tnaItem->is_parallel !== $prevTnaItem->is_parallel) {
            $itemsOrder = $this->commandBus->execute(new SyncCommand($itemsOrder, $tna->id, $tna));
        }
         */
        
        $tnaItem->tna->items_order = json_encode($itemsOrder);
        $tnaItem->tna->save();

        return $itemsOrder;
    }

    /**
     * Update one item in itemsOrder of tna
     *
     * @param object $tnaItem
     * @return jsonarray
     */
    private function updateOneItemOrder($tnaItem)
    {
        $itemsOrder = json_decode($tnaItem->tna->items_order, true);

        if(is_null($tnaItem->dependor_id)) {
            $foundKey = array_search($tnaItem->id, array_column($itemsOrder, 'itemId'));
            $nodes = $itemsOrder[$foundKey]['nodes'];
            $itemsOrder[$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
            $itemsOrder[$foundKey]['nodes'] = $nodes;
        } else {
            $parentKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
            if($parentKey !== null) {
                $foundKey = array_search($tnaItem->id, array_column($itemsOrder[$parentKey]['nodes'], 'itemId'));
                $nodes = $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'];
                $itemsOrder[$parentKey]['nodes'][$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
                $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'] = $nodes;
            }
        }
        return $itemsOrder;
    }

    /**
     * Get task days for an item according to its previous item
     *
     * @param object $tnaItem
     * @param object $prevTnaItem
     * @return integer
     */
    private function getTaskDays($tnaItem, $prevTnaItem)
    {
        $diffInPlannedDate = TNAHelper::diffInDatesAsRealNum($prevTnaItem->planned_date, $tnaItem->planned_date);
        $taskDays = $tnaItem->task_days + $diffInPlannedDate;
        if($taskDays < 0) {
            throw new SeException('Cannot make task days negative', 422, 4200321);
        }
        return $taskDays;
    }

    private function calculateTaskDays($tna, $prevTnaItem, $plannedDate)
    {
        if($prevTnaItem->is_priority_task) return 0;

        $diffInPlannedDate = TNAHelper::diffInDatesAsRealNum($plannedDate, $prevTnaItem->planned_date);
        $taskDays = $prevTnaItem->task_days - $diffInPlannedDate;
        if($taskDays < 0) {
            throw new SeException('Cannot be less than prev item.You should move it upward.', 422, 4200321);
        }
        return $taskDays;
    }

}
