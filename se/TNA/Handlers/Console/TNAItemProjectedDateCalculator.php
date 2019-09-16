<?php

namespace Platform\TNA\Handlers\Console;

use Platform\TNA\Helpers\TNAHelper;

class TNAItemProjectedDateCalculator
{
    /**
     * Calculate Projected date for TNA Item
     *
     * @param Object $item
     * @param json array $itemsOrder
     * @return Object $item
     */
	public function calculate($item, $itemsOrder = NULL, $itemKey = null, $parentKey = null)
	{
        if(is_null($itemsOrder)){
            $itemsOrder = json_decode($item->tna->items_order); 
        }

        if($this->isNode($item)){
            if(is_null($parentKey)) {
                $parentKey = $this->findKey($itemsOrder, $item->dependor_id, 'itemId');
            }
            $projectedDate = $this->calculateForNode($item, $itemsOrder, $itemKey, $parentKey);
        } else {
            if(is_null($itemKey)){
                $itemKey = $this->findKey($itemsOrder, $item->id, 'itemId');
            }
            $projectedDate = $this->calculateForItem($item, $itemsOrder, $itemKey);
        }
        //var_dump($projectedDate);
        return $projectedDate;
    }

    /**
     * Calculate Projected date for Node
     *
     * @param Object $item
     * @param int    $dependorKey
     * @param json array $itemsOrder
     * @return DATE
     */
    private function calculateForNode($item, $itemsOrder, $itemKey, $parentKey)
    {
        /*if($item->tna->status === 'draft') {
            return $item->planned_date;
        }*/

        if($itemKey === 0 && $parentKey === 0) {
            return $item->planned_date;
        } else if($itemKey === 0 && $parentKey !== 0) {
            return TNAHelper::addDayToDate($item->planned_date, $itemsOrder[$parentKey - 1]['delta']);
        } else {
            return TNAHelper::addDayToDate($item->planned_date, $itemsOrder[$parentKey]['nodes'][$itemKey - 1]['delta']);
        }
    }
                
    /**
     * Calculate Projected date for Item
     *
     * @param Object $item
     * @param int    $dependorKey
     * @param json array $itemsOrder
     * @return DATE
     */
    private function calculateForItem($item, $itemsOrder, $itemKey)
    {
        if($itemKey === 0 && empty($itemsOrder[$itemKey]['nodes'])){
            return $item->planned_date;
        } else if($itemKey !== 0 && empty($itemsOrder[$itemKey]['nodes'])){
            return TNAHelper::addDayToDate($item->planned_date, $itemsOrder[$itemKey - 1]['delta']);
        } else {
            return TNAHelper::addDayToDate($item->planned_date, $itemsOrder[$itemKey]['nodes'][count($itemsOrder[$itemKey]['nodes']) - 1]['delta']);
        } 
    }

    /**
     * Check if Item is a node or not
     *
     * @param Object $item
     * @return boolean
     */
    private function isNode($item)
    {
        return !is_null($item->dependor_id);
    }
}
