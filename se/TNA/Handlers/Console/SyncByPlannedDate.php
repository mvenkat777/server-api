<?php
namespace Platform\TNA\Handlers\Console;

use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Models\TNA;
use Platform\TNA\Models\TNAItem;

class SyncByPlannedDate
{
    /**
     * @var Platform\TNA\Models\TNA
     */
    private $tna;

    /**
     * @var DateTime
     */
    private $parallelItemReferencedDate;

    /**
     * @var DateTime
     */
    private $maxItemParallelDate;

    /**
     * @var DateTime
     */
    private $parallelNodeReferencedDate;

    /**
     * @var DateTime
     */
    private $maxNodeParallelDate;

    /**
     * @var DateTime
     */
    private $nodeReferencedDate;

    /**
     * @param Object TNA
     */
    public function __construct(TNA $tna)
    {
        $this->tna = $tna;
    }

    /**
     * Update items order by plannedDate
     *
     * @param json array $itemsOrder
     * @return json array
     */
    public function sync($itemsOrder)
    {
        $itemsOrder = json_decode(json_encode($itemsOrder));
    //foreach itemsorder as item
    //  if item has nodes
    //      update nodes
    //  referencedDate = getReferencedDate
    //  taskDays = item->plannedDate - getReferencedDate
    //  save taskDays
    //return itemsOrder
        foreach($itemsOrder as $key => $item){

            if(!empty($item->nodes)){
                $nodes = $this->syncNodes($item->nodes, $itemsOrder, $key);
            }

            /*
            $this->referenceDate = $this->getItemReferencedDate($item, $itemsOrder, $key);
            $taskDays = TNAHelper::diffInDates($item->plannedDate, $this->referenceDate);
             */
            $taskDays = 0;

            $tnaItem = TNAItem::find($item->itemId);
            $tnaItem->task_days = $taskDays;
            $tnaItem->projected_date = $this->calculateProjectedDate($tnaItem, $key, $itemsOrder);
            $tnaItem->delta = $this->calculateDelta($tnaItem, $key, $itemsOrder);
            $tnaItem->save();

            $itemsOrder[$key]->taskDays = $taskDays;
            $itemsOrder[$key]->projectedDate = $tnaItem->projected_date;
            $itemsOrder[$key]->delta = $tnaItem->delta;
        }

        return $itemsOrder;
    }

    /**
     * Update nodes by plannedDate
     *
     * @param json array $nodes
     * @param json array $itemsOrder
     * @param integer $parentKey
     */
    private function syncNodes($nodes, $itemsOrder, $parentKey)
    {
        foreach($nodes as $nodeKey => $node){
            $this->nodeReferencedDate = $this->getNodeReferencedDate($node, $nodes, $itemsOrder, $nodeKey, $parentKey);

            $taskDays = TNAHelper::diffInDates($node->plannedDate, $this->nodeReferencedDate);

            $tnaItem = TNAItem::find($node->itemId);
            $tnaItem->task_days = $taskDays;
            $tnaItem->save();

            $nodes[$nodeKey]->taskDays = $taskDays;
        }

        return $nodes;
    }

    /**
     * Get reference date for item
     *
     * @param Object $item
     * @param json array $itemsOrder
     * @param integer $itemKey
     */
    private function getItemReferencedDate($item, $itemsOrder, $key)
    {
    //  if item is first milestone
        if($key === 0){
    //      if item is first parallel
            if($item->isParallel){
    //          parallelReferencedDate = tna referenced date
                $this->parallelItemReferencedDate = TNAHelper::getReferenceDate($this->tna);
    //          maxParallelDate = item->plannedDate
                $this->maxItemParallelDate = $item->plannedDate;
            }
    //      if item has nodes
            if(!empty($item->nodes)){
    //          return nodes last item plannedDate
                return $this->getEligiblePlannedDate($item->nodes);
            }
    //      return tna referenced date
            return TNAHelper::getReferenceDate($this->tna);
        }
    //  if item is parallel
        if($item->isParallel){
    //      if item is first parallel
            if($this->isFirstParallel($key, $itemsOrder)){
    //          parallelReferencedDate = previous milestone date
                $this->parallelItemReferencedDate = $itemsOrder[$key - 1]->plannedDate;
    //          maxParallelDate = item->plannedDate
                $this->maxItemParallelDate = $item->plannedDate;
            }
    //      else
            else{
    //          maxParallelDate = item->plannedDate > maxParalleDate ? item->plannedDate : maxParallelDate
                $this->maxItemParallelDate = $item->plannedDate > $this->maxItemParallelDate ? $item->plannedDate : $this->maxItemParallelDate;
            }
    //      if item has nodes
            if(!empty($item->nodes)){
    //          return last item plannedDate
                return $this->getEligiblePlannedDate($item->nodes);
            }
    //      else
            else{
    //          return parallelReferencedDate
                return $this->parallelItemReferencedDate;
            }
        }
    //  else
        else{
    //      if item is after parallel task
    //        if($this->isAfterParallel($key, $itemsOrder)){
    //          return maxParallelDate
    //          return $this->maxItemParallelDate;
    //      }
    //      else
    //      else{
    //          if item has nodes
                if(!empty($item->nodes)){
    //              return last node plannedDate
                    return $this->getEligiblePlannedDate($item->nodes);
                }
    //          else
                else{
    //              return previous item plannedDate
                    return $itemsOrder[$key - 1]->plannedDate;
                }
    //      }
        }
    }

    /**
     * Get Referenced Date for node
     *
     * @param Object $node
     * @param json array $nodes
     * @param json array $itemsOrder
     * @param integer $nodeKey
     * @param integer $parentKey
     *
     * @return DATE
     */
    private function getNodeReferencedDate($node, $nodes, $itemsOrder, $nodeKey, $parentKey)
    {
    //  if node is first task in tna
        if($nodeKey === 0 && $parentKey === 0){
    //      if node is first parallel
            if($this->isFirstParallel($nodeKey, $nodes)){
    //          parallelReferencedDateForNode = tna referenced
                $this->parallelNodeReferencedDate = TNAHelper::getReferenceDate($this->tna);
    //          maxParallelDateForNode = node->plannedDate
                $this->maxNodeParallelDate = $node->plannedDate;
            }
    //      return tna referenced date
            return TNAHelper::getReferenceDate($this->tna);
        }
    //  if node is first task in milestone not tna
        if($nodeKey === 0 && $parentKey !== 0){
    //      if node is first parallel
            if($this->isFirstParallel($nodeKey, $nodes)){
    //          parallelReferencedDateForNode = getReferencedDateByMilestone
                $this->parallelNodeReferencedDate = $this->getReferencedDateByMilestone($nodeKey, $parentKey, $itemsOrder, $nodes);
    //          maxParallelDateForNode = node->plannedDate
                $this->maxNodeParallelDate = $node->plannedDate;
            }
    //      return getReferencedDateByMileStone
            return $this->getReferencedDateByMilestone($nodeKey, $parentKey, $itemsOrder, $nodes);
        }
    //  if node is parallel
        if($node->isParallel){
    //      if node is first parallel
            if($this->isFirstParallel($nodeKey, $nodes)){
    //          parallelReferencedDateForNode = getReferencedDateByMileStone
                $this->parallelNodeReferencedDate = $this->getReferencedDateByMilestone($nodeKey, $parentKey, $itemsOrder, $nodes);
    //          maxParallelDateForNode = node->plannedDate
                $this->maxNodeParallelDate = $node->plannedDate;
            }
    //      else
            else{
    //          maxParallelDateForNode = node->plannedDate > maxParallelDateForNode > node->plannedDate : maxParallelDateForNode
                $this->maxNodeParallelDate = $node->plannedDate > $this->maxNodeParallelDate ? $node->plannedDate : $this->maxNodeParallelDate;
            }
    //      return parallelReferencedDateForNode
            return $this->parallelNodeReferencedDate;
        }
    //  else
        else{
    //      if node is after parallel task
            if($this->isAfterParallel($nodeKey, $nodes)){
    //          return maxParallelDateForNode
                return $this->maxNodeParallelDate;
            }
    //      return previous node plannnedDate
            return $nodes[$nodeKey - 1]->plannedDate;
        }
    }

    /**
     * Get Referenced Date By MileStone
     *
     * @param integer $nodeKey
     * @param integer $parentKey
     * @param json array $itemsOrder
     * @param json array $nodes
     *
     * @return DATE
     */
    private function getReferencedDateByMilestone($nodeKey, $parentKey, $itemsOrder, $nodes)
    {
    //  $parentItem = $itemsOrder[$parentKey];
        $parentItem = $itemsOrder[$parentKey];
    //  
    //  if $nodeKey == 0
        if($nodeKey === 0){
    //      if $parent is parallel
            if($parentItem->isParallel){
    //          if parent is not first parallel
                if(!$this->isFirstParallel($parentKey, $itemsOrder)){
    //              return parallelReferencedDate for item
                    return $this->parallelItemReferencedDate;
                }
            }
    //      return previous milestone plannedDate
            return $itemsOrder[$parentKey - 1]->plannedDate;
        }
        else{
    //      return previous node plannedDate
            return $nodes[$nodeKey - 1]->plannedDate;
        }
    }

    /**
     * Check if task  is first parallel item or not
     *
     * @param integer $key
     * @param json array $array
     *
     * @return boolean
     */
    private function isFirstParallel($key, $array)
    {
        if($key < 1){
            return $array[$key]->isParallel;
        }

        return !$array[$key - 1]->isParallel;
    }

    /**
     * Check if task is after a parallel task  or not
     *
     * @param integer $key
     * @param json array $array
     *
     * @return boolean
     */
    private function isAfterParallel($key, $array)
    {
        if($key < 1){
            return $array[$key]->isParallel;
        }

        return $array[$key - 1]->isParallel;
    }

    /**
     * Get planned date of eligible node/task (mainly last item in node)
     *
     * @param json array $nodes
     *
     * @return DATE
     */
    private function getEligiblePlannedDate($nodes)
    {
        return $nodes[count($nodes) - 1]->plannedDate;
    }

    private function calculateProjectedDate($tnaItem, $key, $itemsOrder)
    {
        if(($key === 0 && empty($itemsOrder[0]->nodes)) || $this->tna->state === 'draft'){
            return $tnaItem->planned_date;
        }
        if(!empty($itemsOrder[$key]->nodes)){
            $nodes = $itemsOrder[$key]->nodes;
            return TNAHelper::addDayToDate($tnaItem->planned_date, $nodes[count($nodes) - 1]->delta)->toDateTimeString();
        }
        return TNAHelper::addDayToDate($tnaItem->planned_date, $itemsOrder[$key - 1]->delta)->toDateTimeString();

    }

    private function calculateDelta($tnaItem, $key, $itemsOrder)
    {
        $sign = '';
        if($itemsOrder[$key]->itemStatus === 'closed'){
            if($tnaItem->planned_date > $tnaItem->actual_date){
                $sign = '-';
            } else if($tnaItem->planned_date < $tnaItem->actual_date){
                $sign = '+';
            }

            return $sign.TNAHelper::diffInDates($tnaItem->planned_date, $this->tna->actual_date);
        }

        if($tnaItem->planned_date > $tnaItem->projected_date){
            $sign = '-';
        } else if($tnaItem->planned_date < $tnaItem->projected_date){
            $sign = '+';
        }
        return $sign.TNAHelper::diffInDates($tnaItem->planned_date, $tnaItem->projected_date);
    }

}
