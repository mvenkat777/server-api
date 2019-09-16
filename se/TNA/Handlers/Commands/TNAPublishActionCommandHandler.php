<?php

namespace Platform\TNA\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Helpers\TaskDispatcher;

class TNAPublishActionCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\TNA\Helpers\TaskDispatcher
     */
    protected $taskDispatcher;

    /**
     * @var jsonArray
     */
    private $itemsOrder;

	/**
     * @param TaskDispatcher      $taskDispatcher
	 */
    public function __construct(TaskDispatcher $taskDispatcher)
	{
        $this->taskDispatcher = $taskDispatcher;
	}

	/**
	 * @param  TNAPublishActionCommand $command 
	 * @return TNA (Model)          
	 */
	public function handle($command)
	{ 
        $itemsOrder = json_decode($command->tna->items_order);
        $this->itemsOrder = $this->removePriorityFromItemsOrder($itemsOrder);

        if($this->lastItemIsDispatched($itemsOrder)){
            return;
        }

        $currentItemKey = $this->getCurrentTaskKey($itemsOrder);
        $currentItem = $itemsOrder[$currentItemKey];

        if($currentItem->isParallel){
                    for($i = $currentItemKey; $i < count($itemsOrder); $i++){
                        if($itemsOrder[$i]->isParallel){
                            $this->checkAndDispatchTask($itemsOrder[$i]);
                        } else {
                            break;
                            $i = count($itemsOrder);
                        }
                    }
        } else {
            $this->checkAndDispatchTask($currentItem);
        }

        /**
        $command->tna->items_order = json_encode($this->itemsOrder);
        $command->tna->save();
         */
    }

    /**
     * Check if last item is dispatched or not
     *
     * @param $taskList jsonArray
     * @return boolean
     */
    private function lastItemIsDispatched($taskList)
    {
        if(count($taskList) < 1){
            return true;
        }

        return $taskList[count($taskList) - 1]->isDispatched;
    }

    /**
     * Check which item should be dispatched as task and dispatch the task
     *
     * @param $task Object
     * @return 
     */
    private function checkAndDispatchTask($task)
    {
        if($this->isEligible($task)){
            return $this->dispatchTask($task);
        }            

        if(!empty($task->nodes)){
            $currentNodeKey = $this->getCurrentTaskKey($task->nodes);
            $currentNode = $task->nodes[$currentNodeKey];

            /*
            if(isset($currentNode->isPriorityTask) && $currentNode->isPriorityTask) {
                //echo "yess\n";
                for($j = $currentNodeKey; $j < count($task->nodes); $j++) {
                    //echo $j;
                    if(isset($task->nodes[$j]->isPriorityTask) && $task->nodes[$j]->isPriorityTask) {
                        //echo 'going'.'\n';
                        $this->checkAndDispatchTask($task->nodes[$j]);
                    } else {
                        $currentNodeKey = $j;
                        $currentNode = $task->nodes[$currentNodeKey];
                        $this->checkAndDispatchTask($task->nodes[$j]);
                        break;
                    }
                }
            }
             */

            if($currentNode->isParallel){
                for($i = $currentNodeKey; $i < count($task->nodes); $i++){
                    if($task->nodes[$i]->isParallel){
                        $this->checkAndDispatchTask($task->nodes[$i]);
                    } else {
                        $i = count($task->nodes);
                    }
                }
            } else {
                return $this->checkAndDispatchTask($currentNode);
            }
        }
    } 

    /**
     * Check if the item is eligible to be an task
     *
     * @param $task Object
     * @return boolean
     */
    private function isEligible($task)
    {
        if(!empty($task->nodes)){
            return $this->checkAllTasksAreComleted($task->nodes) && (!$task->isDispatched && !$task->isCompleted);
        }

        return (!$task->isDispatched && !$task->isCompleted);    
    }

    /**
     * Get current task key to work on i.e which task is not completed
     *
     * @param $taskList jsonArray
     * @return boolean
     */
    private function getCurrentTaskKey($taskList)
    {
        foreach($taskList as $key => $task){
            if(!$task->isCompleted){
                return $key;
            }
        }
        return count($taskList) - 1;
    }

    /**
     * Check all the tasks in given list are completed or not
     *
     * @param $taskList jsonArray
     * @return boolean
     */
    private function checkAllTasksAreComleted($taskList)
    {
        foreach($taskList as $task){
            if(!$task->isCompleted){
                return false;
            }
        }
        return true;
    }

    /**
     * Create task from tnaItem
     */
    private function dispatchTask($task)
    {
        echo "dispatching";
        $this->taskDispatcher->dispatch($task);
    }

    private function removePriorityFromItemsOrder($itemsOrder)
    {
        foreach($itemsOrder as $key => $item){
            foreach($item->nodes as $nodeKey => $node) {
                if(isset($node->isPriorityTask) && $node->isPriorityTask) {
                    unset($item->nodes[$nodeKey]);
                }
            }
            $itemsOrder[$key]->nodes = array_values($item->nodes);
        }

        return $itemsOrder;
    }

}

