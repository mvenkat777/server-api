<?php

namespace Platform\TNA\Helpers;

use Platform\Tasks\Helpers\TaskHelper;
use Platform\TNA\Helpers\TNAHelper;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\Tasks\Commands\CreateTaskCommand;
use Platform\TNA\Models\TNAItem;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;

class TaskDispatcher
{
	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepository;

	/**
	 * @param DefaultCommandBus $commandBus       
	 * @param TNAItemRepository $tnaItemRepository 
	 */
	public function __construct(DefaultCommandBus $commandBus,
								TNAItemRepository $tnaItemRepository)
	{
		$this->commandBus = $commandBus;
		$this->tnaItemRepository = $tnaItemRepository;
	}

    /**
     * Create task from tnaItem in task app
     *
     * @param Object $tnaItem
     */
    public function dispatch($tnaItem, $itemsOrder = null)
    {
        $item = ($tnaItem instanceof TNAItem) ? $tnaItem : $this->tnaItemRepository->getById($tnaItem->itemId);

        if(is_null($item)) {
            throw new SeException('Item doesnot exist', 422, 4200223);
        }

        $tnaItem = TNAHelper::getTransformedItem($item);
        $itemsOrder = is_null($itemsOrder) ? json_decode($item->tna->items_order, true) : json_decode(json_encode($itemsOrder), true);

		$taskData = $this->getTaskData($tnaItem, $item->tna);

		\DB::beginTransaction();
        $dispatchedTask = $this->commandBus->execute(new CreateTaskCommand($taskData));
        $item = $this->tnaItemRepository->dispatch($tnaItem->itemId, $dispatchedTask->id);
        $itemsOrder = TNAHelper::updateOneItemOrder($item, $itemsOrder);

        $item->tna->items_order = json_encode($itemsOrder);
        $item->tna->save();
		\DB::commit();

        return $itemsOrder;
    }

	/**
	 * Get the data required for creating a task
	 * 
	 * @param  object or TNAItem $tnaItem 
	 * @return 
	 */
	private function getTaskData($tnaItem, $tna)
	{
		try{
            $taskDueDate = $tnaItem->plannedDate;
            /*
            if(Carbon::parse($tnaItem->plannedDate)->toDateString() < Carbon::now()->toDateString()){
                $taskDueDate = $tnaItem->projectedDate;
            }
             */
            return [
                'creatorId' => $tna->representor->id,
                'title' => $tnaItem->title,
                'category' => 'Calendar',
                'assignee' => $tnaItem->representor->email,
                'dueDate' => $taskDueDate,
                'priorityId' => TaskHelper::getPriorityId('highest'),
                'tags' => TNAHelper::getTagsForTask($tna),
                'description' => $tnaItem->description,
                'tnaItemId' => $tnaItem->itemId,
                'skipCheck' => true
            ];
		}
		catch(Exception $e){
			throw new SeException($e->getMessage, 500, 50000);
		}
	}

}
