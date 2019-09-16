<?php
namespace Platform\Tasks\Observers;

use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNARepository;

class TaskObserver
{
	/**
     * @param Object Task
     * @return mixed
     */
	public function updated($task)
	{
		// $fieldForUpdateTNA = ['status_id', 'title', 'description', 'assignee_id'];
		// $updatedFields = $task->getDirty();
		// foreach ($updatedFields as $key => $updatedField) {
		// 	if(in_array($key, $fieldForUpdateTNA)) {
		// 		$this->updateTNAItem($task);
		// 	}
		// }
		$this->updateTNAItem($task);
	}
	
	public function created($arr){
	}

	public function deleted($arr){
	}

	private function updateTNAItem($task)
	{
		if(!is_null($task->tnaItem)){
			$tnaItem = $task->tnaItem;
			// $taskDays = TNAHelper::getTaskDays($task->due_date, $tnaItem);
			$tnaItem->title = $task->title;
			$tnaItem->description = $task->description;
			$tnaItem->representor_id = $task->assignee_id;
			// $tnaItem->planned_date = $task->due_date;
			$tnaItem->item_status_id = $task->status_id;
			if($tnaItem->save()){
				$itemsOrder = $this->updateOneItemOrder($tnaItem);
                $tnaItem->tna->items_order = json_encode($itemsOrder);
                $tnaItem->tna->save();
			}
		}
	}

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
}
