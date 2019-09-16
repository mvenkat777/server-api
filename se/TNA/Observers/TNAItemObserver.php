<?php
namespace Platform\TNA\Observers;

use Platform\App\Exceptions\SeException;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Repositories\Eloquent\EloquentTNARepository;

class TNAItemObserver
{
	/**
     * @param array $arr
     * @return mixed
     */
	public function updated($tnaItem)
    {
		
	}

	public function created($tnaItem){
        /*$tna = $tnaItem->tna;
        $itemsOrder = json_decode($tna->items_order);
        $tnaItem->isFromCreate = true;
        $itemsOrder[count($itemsOrder)] = TNAHelper::getTransformedItem($tnaItem);

        $itemsOrder = TNAHelper::sortItemsOrder($itemsOrder);
        $foundKey = array_search($tnaItem->id, array_column(json_decode(json_encode($itemsOrder), true), 'itemId'));
        $taskDays = TNAHelper::calculateTaskDays($tna, $itemsOrder, $foundKey);
        $tnaItem->task_days = $taskDays;
        $itemsOrder[$foundKey]->taskDays = $taskDays;
        $tna->items_order = json_encode($itemsOrder);
        $tna = (new TNAProjectedDateCalculator)->calculate($tna);
        // $tna = (new TNAHealthCalculator)->calculate($tna);
        unset($tnaItem->isFromCreate);
        if(!$tna->save() || !$tnaItem->save()){
            throw new SeException('Cannot add itemOrder', 422, 4220422);
        }*/
	}

	public function deleted($arr){
	}

}
