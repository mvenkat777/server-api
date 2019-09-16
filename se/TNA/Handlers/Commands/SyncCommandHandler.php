<?php
namespace Platform\TNA\Handlers\Commands;

use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Models\TNA;
use Platform\TNA\Models\TNAItem;
use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;

class SyncCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\TNA\Models\TNA
     */
    private $tna;

    /**
     * @var Object
     */
    private $previousItem;

    /**
     * @var Object
     */
    private $previousNode;

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

    private $setMaxParallelDate;

    private $calculateMaxParallelDate;

    private $tnaRepository;

    public function __construct(TNARepository $tnaRepository)
    {
		$this->tnaRepository = $tnaRepository;
    }

    /**
     * Update items order by plannedDate
     *
     * @param json array $itemsOrder
     * @return json array
     */
    public function handle($command)
    {
        if(is_null($command->tna)){
            $this->tna = $this->tnaRepository->getById($command->tnaId);
        } else {
            $this->tna = $command->tna;
        }
        $this->tna->items_order = json_encode($command->itemsOrder);
        $this->tna->save();
        $this->tna->setCustomMessage($this->tna->title.'\'s items order has been changed')
                    ->recordCustomActivity($this->tna, ['Tna',[]]);
        $this->tna  = (new TNAProjectedDateCalculator)->calculate($this->tna, $command->itemsOrder);
        return $this->tna;

        $itemsOrder = json_decode(json_encode($command->itemsOrder));

        $plannedDate = TNAHelper::getReferenceDate($this->tna);
        $this->previousItem = json_decode(
            json_encode([
                'plannedDate' => is_object($plannedDate)
                                 ? $plannedDate->toDateTimeString() 
                                 : $plannedDate,
                'delta' => 0
            ])); 

        \DB::beginTransaction();
        foreach($itemsOrder as $key => $item){
            //echo $key.' | ';

            if(!empty($item->nodes)){
                $itemsOrder[$key]->nodes = $this->syncNodes($item->nodes, $itemsOrder, $key);
            }

            /*
            $this->referenceDate = $this->getItemReferencedDate($item, $itemsOrder, $key);
            $plannedDate = TNAHelper::addDayToDate($this->referenceDate, $item->taskDays)->toDateTimeString();
             */
            $plannedDate = $this->getItemReferencedDate($item, $itemsOrder, $key);

            $tnaItem = TNAItem::find($item->itemId);
            $tnaItem->planned_date = $plannedDate;
            $tnaItem->task_days = 0;
            $tnaItem->projected_date = $this->calculateProjectedDate($tnaItem, $key, $itemsOrder);
            $tnaItem->delta = $this->calculateDelta($tnaItem, $key, $itemsOrder);
            $tnaItem->save();

            $itemsOrder[$key]->plannedDate = $tnaItem->planned_date;
            $itemsOrder[$key]->projectedDate = $tnaItem->projected_date;
            $itemsOrder[$key]->delta = $tnaItem->delta;
            $itemsOrder[$key]->taskDays = 0;

            $this->previousItem = $itemsOrder[$key];
        }

        (new TNAProjectedDateCalculator)->calculate($this->tna, $itemsOrder);

        \DB::commit();
        $this->tna->items_order = json_encode($itemsOrder);
        $this->tna->save();

        return $itemsOrder;
    }

    /**
     * Calculate projected date of tna item according to previous item or node delta
     *
     * @param Object $tnaItem
     * @param integer $key
     * @param json array $itemsOrder
     *
     * @return DATETIME
     */
    private function calculateProjectedDate($tnaItem, $key, $itemsOrder)
    {
        if(($key === 0 && empty($itemsOrder[0]->nodes)) || $this->tna->state === 'draft'){
            return $tnaItem->planned_date;
        }
        if(!empty($itemsOrder[$key]->nodes)){
            $nodes = $itemsOrder[$key]->nodes;
            return TNAHelper::addDayToDate($tnaItem->planned_date, $this->previousNode->delta)->toDateTimeString();
        }
        return TNAHelper::addDayToDate($tnaItem->planned_date, $this->previousItem->delta)->toDateTimeString();

    }

    /**
     * Calculate delta of tna item according to its plannedDate and previous item actualDate
     *
     * @param object $tnaItem
     * @param integer $key
     * @param json array $itemsOrder
     *
     * @return string
     */
    private function calculateDelta($tnaItem, $key, $itemsOrder)
    {
        $sign = '';
        if($itemsOrder[$key]->itemStatus === 'closed'){
            if($tnaItem->planned_date > $tnaItem->actual_date){
                $sign = '-';
            } else if($tnaItem->planned_date < $tnaItem->actual_date){
                $sign = '+';
            }

            return $sign.TNAHelper::diffInDates($tnaItem->planned_date, $tnaItem->actual_date);
        }

        if($tnaItem->planned_date > $tnaItem->projected_date){
            $sign = '-';
        } else if($tnaItem->planned_date < $tnaItem->projected_date){
            $sign = '+';
        }

        return $sign.TNAHelper::diffInDates($tnaItem->planned_date, $tnaItem->projected_date);
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
        $this->previousNode = $parentKey === 0 ?  $this->previousItem
                                                : $itemsOrder[$parentKey];
        foreach($nodes as $nodeKey => $node){
            if(isset($node->isPriorityTask) && $node->isPriorityTask) {
                continue;
            }
            //echo $nodeKey.' ->';
            $this->nodeReferencedDate = $this->getNodeReferencedDate($node, $nodes, $itemsOrder, $nodeKey, $parentKey);

            //echo '(maxparallel '.$this->maxNodeParallelDate.') '; 
            $plannedDate = TNAHelper::addDayToDate($this->nodeReferencedDate, $node->taskDays)->toDateTimeString();
            //echo $this->nodeReferencedDate.' + '.$node->taskDays.' = '.$plannedDate.' ';

            $tnaItem = TNAItem::find($node->itemId);
            $tnaItem->planned_date = $plannedDate;
            $tnaItem->task_days = $node->taskDays;
            $tnaItem->projected_date = $this->calculateNodeProjectedDate($plannedDate, $nodeKey, $parentKey, $itemsOrder, $nodes);
            $tnaItem->delta = $this->calculateDelta($tnaItem, $nodeKey, $nodes);
            $tnaItem->save();

            $nodes[$nodeKey]->plannedDate = $tnaItem->planned_date;
            $nodes[$nodeKey]->projectedDate = $tnaItem->projected_date;
            $nodes[$nodeKey]->delta = $tnaItem->delta;

            $this->previousNode = $nodes[$nodeKey];
        }

        //return TNAHelper::sortItemsOrder($nodes, true);
        return $nodes;
    }

    private function calculateNodeProjectedDate($plannedDate, $nodeKey, $parentKey, $itemsOrder, $nodes)
    {
        if(($nodeKey === 0 && $parentKey === 0) || ($this->tna->state === 'draft')){
            return $plannedDate;
        }

        if($nodeKey === 0 && $parentKey !== 0){
            return TNAHelper::addDayToDate($plannedDate, $itemsOrder[$parentKey - 1]->delta)->toDateTimeString();
        }
        return TNAHelper::addDayToDate($plannedDate, $this->previousNode->delta)->toDateTimeString();
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
                $this->maxItemParallelDate = $this->parallelItemReferencedDate;
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
                $this->maxItemParallelDate = $this->parallelItemReferencedDate;
            }
    //      else
            else{
    //          maxParallelDate = item->plannedDate > maxParalleDate ? item->plannedDate : maxParallelDate
                $this->maxItemParallelDate = $this->parallelItemReferencedDate > $this->maxItemParallelDate ? $this->parallelItemReferencedDate : $this->maxItemParallelDate;
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
            //if($this->isAfterParallel($key, $itemsOrder)){
    //          return maxParallelDate
              //  return $this->maxItemParallelDate;
            //}
    //      else
            //else{
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
            //}
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
                $this->maxNodeParallelDate = TNAHelper::getReferenceDate($this->tna);
                //$this->setMaxParallelDate = true;
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
                $this->maxNodeParallelDate = $this->getReferencedDateByMilestone($nodeKey, $parentKey, $itemsOrder, $nodes);
                //$this->setMaxParallelDate = true;
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
                $this->maxNodeParallelDate = $this->parallelNodeReferencedDate;
                //$this->setMaxParallelDate = true;
            }
    //      else
            else{
    //          maxParallelDateForNode = node->plannedDate > maxParallelDateForNode > node->plannedDate : maxParallelDateForNode
                $this->maxNodeParallelDate = $this->parallelNodeReferencedDate > $this->maxNodeParallelDate ? $this->parallelNodeReferencedDate : $this->maxNodeParallelDate;
                //$this->setMaxParallelDate = true;
                //$this->calculateMaxParallelDate = true;
            }
    //      return parallelReferencedDateForNode
            return $this->parallelNodeReferencedDate;
        }
    //  else
        else{
    //      if node is after parallel task
            //if($this->isAfterParallel($nodeKey, $nodes)){
    //          return maxParallelDateForNode
                //$this->setMaxParallelDate = false;
                //$this->calculateMaxParallelDate = false;
               // return $this->maxNodeParallelDate;
           // }
    //      return previous node plannnedDate
            //$this->setMaxParallelDate = false;
            //$this->calculateMaxParallelDate = false;

            return $this->previousNode->plannedDate;
            //return $nodes[$nodeKey - 1]->plannedDate;
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
            return $this->previousItem->plannedDate;
            //return $itemsOrder[$parentKey - 1]->plannedDate;
        }
    //  else
        else{
    //      return previous node plannedDate
            return $this->previousNode->plannedDate;
            //return $nodes[$nodeKey - 1]->plannedDate;
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
        if($nodes[count($nodes) - 1]->isParallel) {
            return $this->maxNodeParallelDate;
        }

        return $this->previousNode->plannedDate;
    }

    /*
    public function sync1($itemsOrder)
    {
        dd('dd');
        \DB::beginTransaction();
        $taskDays = null;
        $prevPlannedDateForParallel = null;
        $prevMaxParallelPlannedDate = null;
        foreach ($itemsOrder as $key => $itemOrder) {

            if(!empty($itemOrder['nodes'])) {
                $itemsOrder[$key]['nodes'] = $this->syncNodes($itemOrder['nodes'], $itemsOrder, $key);
            }

            if($key === 0 && empty($itemsOrder[$key]['nodes'])){
                var_dump('ss');
                $taskDays = TNAHelper::diffInDates($itemOrder['plannedDate'], $this->tna->start_date);
            } else if($key === 0 && !empty($itemsOrder[$key]['nodes'])){
                var_dump('ss');
                $taskDays = TNAHelper::diffInDates($itemOrder['plannedDate'], $itemOrder['nodes'][count($itemOrder['nodes']) - 1]['plannedDate']);
            } else {
                if(isset($node['isParallel']) && $node['isParallel']){
                   if(empty($itemOrder['nodes'])){
                        if(!$itemsOrder[$key - 1]['isParallel']){
                            $prevPlannedDateForParallel = $itemsOrder[$key - 1]['plannedDate'];
                            $prevMaxParallelPlannedDate = $prevPlannedDateForParallel;
                        } else {
                            $prevMaxParallelPlannedDate = Carbon::parse($itemsOrder[$key - 1]['plannedDate']) > Carbon::parse($itemOrder['plannedDate']) 
										            ? $itemsOrder[$key - 1]['plannedDate'] 
										            : $itemOrder['plannedDate'];
                        }
                        
                        $taskDays = TNAHelper::diffInDates($itemOrder['plannedDate'], $prevPlannedDateForParallel); 
                   } else {
                        if($itemsOrder[$key - 1]['isParallel']){
                            $taskDays = TNAHelper::diffInDates($itemOrder['plannedDate'], $prevMaxParallelPlannedDate);
                        } else {
                            $taskDays = TNAHelper::diffInDates($itemOrder['plannedDate'], $itemsOrder[$key - 1]['plannedDate']);
                        }
                   }
                }
            }


            $this->syncItem($itemOrder['itemId'], $taskDays);

            $itemsOrder[$key]['taskDays'] = $taskDays;

            $nodes = $itemsOrder[$key]['nodes'];
            $itemsOrder[$key] = TNAHelper::getTransFormedItem($this->syncItem($itemOrder, $taskDays));
            $itemsOrder[$key]['nodes'] = $nodes;

        }

        if(!is_null($tna)) {
            $tna->items_order = json_encode($itemsOrder);
            $tna->save();
        }
        \DB::commit();
        return $itemsOrder;
    }

    private function getPrevPlannedDate($tna, $itemsOrder, $key)
    {
        if($key === 0) {
            return TNAHelper::getReferenceDate($tna);
        } else {
            if(!empty($itemsOrder[$key - 1]->nodes)){
                return max(array_column(json_decode(json_encode($itemsOrder[$key - 1]->nodes), true), 'plannedDate'));
            }
            return $itemsOrder[$key - 1]->plannedDate;
        }
    }

    private function syncNodes($nodes, $itemsOrder, $parentKey)
    {
        $taskDays = null;
        $prevPlannedDateForParallel = null;
        $prevMaxParallelPlannedDate = null;
        foreach($nodes as $nodeKey => $node){
            if($nodeKey === 0 && $parentKey === 0){
                $taskDays = TNAHelper::diffInDates($node['plannedDate'], $this->tna->start_date);
            } else if($nodeKey === 0 && $parentKey !== 0){
                $taskDays = TNAHelper::diffInDates($node['plannedDate'], $itemsOrder[$parentKey - 1]['plannedDate']);
            } else {
                if(isset($node['isParallel']) && $node['isParallel']){
                   if(isset($nodes[$nodeKey - 1]['isParallel']) && !$nodes[$nodeKey - 1]['isParallel']){ 
                       $prevPlannedDateForParallel = $nodes[$nodeKey - 1]['plannedDate'];
                       $prevMaxParallelPlannedDate = $prevPlannedDateForParallel;
                   } else {
                       $prevMaxParallelPlannedDate = Carbon::parse($nodes[$nodeKey - 1]['plannedDate']) > Carbon::parse($node['plannedDate']) 
										            ? $nodes[$nodeKey - 1]['plannedDate'] 
										            : $node['plannedDate'];
                   }
                   $taskDays = TNAHelper::diffInDates($node['plannedDate'], $prevPlannedDateForParallel);
                } else {
                    if(isset($nodes[$nodeKey - 1]['isParallel']) && $nodes[$nodeKey - 1]['isParallel']){
                        $taskDays = TNAHelper::diffInDates($node['plannedDate'], $prevMaxParallelPlannedDate);
                    } else {
                        $taskDays = TNAHelper::diffInDates($node['plannedDate'], $nodes[$nodeKey - 1]['plannedDate']);
                    }
                }
            }

            $this->syncItem($node['itemId'], $taskDays, $itemsOrder[$parentKey]['itemId']);
            $nodes[$nodeKey]['taskDays'] = $taskDays;
        }
        return $nodes;
    }

    private function syncItem($itemId, $taskDays, $dependorId = null)
    {
        $updatedItem = \Platform\TNA\Models\TNAItem::find($itemId);
        if(is_null($taskDays)){
            var_dump($updatedItem->title);
            die('dd');
        }
        if($updatedItem) {
            $updatedItem->task_days = $taskDays;
            $updatedItem->dependor_id = $dependorId;
            $updatedItem->save();
            return $updatedItem;
        } else {
            throw new SeException('Item not found', 422, 4200132);
        }
    }
     */
}
