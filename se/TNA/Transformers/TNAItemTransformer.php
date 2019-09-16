<?php

namespace Platform\TNA\Transformers;

use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Models\TNAItem;
use Platform\TNA\Transformers\MetaTNAItemTransformer;
use Platform\TNA\Transformers\TNAVisibilityTransformer;
use Platform\Tasks\Transformers\TaskTransformer;
use Platform\Users\Transformers\MetaUserTransformer;

class TNAItemTransformer extends TransformerAbstract 
{
	private $delta;

	private $deltaSign;

    private $projectedDate;

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(TNAItem $tnaItem)
	{
		$creator = $this->item($tnaItem->creator, new MetaUserTransformer);
		$creator = $this->manager->createData($creator)->toArray();

		$representor = $this->item($tnaItem->representor, new MetaUserTransformer);
		$representor = $this->manager->createData($representor)->toArray();

		if(!is_null($tnaItem->dependor)){
			$dependor = $this->item($tnaItem->dependor, new MetaTNAItemTransformer);
			$dependor = $this->manager->createData($dependor)->toArray();
		}
		else{
			$dependor['data'] = NULL;
		}

		$visibility = $this->collection($tnaItem->visibility, new TNAVisibilityTransformer);
		$visibility = $this->manager->createData($visibility)->toArray();

		if(!is_null($tnaItem->task)){
			$task = $this->item($tnaItem->task, new TaskTransformer);
			$task = $this->manager->createData($task)->toArray();
		}
		else{
			$task['data'] = NULL;
		}

		$transformedData = [
			'itemId' => $tnaItem->id,
			'title' => $tnaItem->title,
			'description' => $tnaItem->description,
			'creator' => $creator['data'],
			'taskDays' => $tnaItem->task_days,
			'plannedDate' => is_object($tnaItem->planned_date) 
								? $tnaItem->planned_date->toDateTimeString()
								: $tnaItem->planned_date,
			'actualDate' => $tnaItem->actual_date,
			'representor' => $representor['data'],
			'dependor' => $dependor['data'],
			'isMilestone' => $tnaItem->is_milestone,
			'isCompleted' => $this->isItemCompleted($tnaItem),
			'isDispatched' => $this->isItemDispatched($tnaItem),
			'visibility' => $visibility['data'],
			'tnaId' => $tnaItem->tna_id,
			'nodes' => [],
			'itemStatus' => $this->getTNAItemStatus($tnaItem),
			//'delta' => $tnaItem->delta,
			//'deltaSign' => $this->getDeltaSign($tnaItem),
			'projectedDate' => $tnaItem->projected_date,
            'department' => isset($tnaItem->department->department) ? $tnaItem->department->department : null,
            //'isParallel' => $tnaItem->is_parallel,
            //'isPriorityTask' => $tnaItem->is_priority_task,
			// 'week' => $week,
			'task' => $task['data'],
			'createdAt' => $tnaItem->created_at->toDateTimeString(),
			'updatedAt' => $tnaItem->updated_at->toDateTimeString()
		];

		if(!is_null($tnaItem->tna->projected_date)){
			$transformedData['tna']['projectedDate'] = Carbon::parse($tnaItem->tna->projected_date)->toDateTimeString();
		}
		else{
			$transformedData['tna']['projectedDate'] = NULL;
		}
        $transformedData['tna']['tnaHealth'] = $tnaItem->tna->health->health;
		$transformedData['tna']['state'] = $tnaItem->tna->state->state;

		if(isset($tnaItem->itemsOrder)){
	        $transformedData['itemsOrder'] = $tnaItem->itemsOrder;
        }

		return $transformedData;
	}

	private function isItemCompleted($tnaItem)
	{
		return is_null($tnaItem->is_completed) ? false : $tnaItem->is_completed;
	}

	private function isItemDispatched($tnaItem)
	{
		return is_null($tnaItem->is_dispatched) ? false : $tnaItem->is_dispatched;
	}

	/**
	 * Get TNAItem status depending on the item completed/dispatched
	 * @param  [type] $tnaItem [description]
	 * @return [type]          [description]
	 */
	private function getTNAItemStatus($tnaItem)
	{
		$isCompleted = $this->isItemCompleted($tnaItem);
		$isDispatched = $this->isItemDispatched($tnaItem);

		if($isDispatched && !$isCompleted)
			return 'active';
		elseif ($isCompleted && $isDispatched)
			return 'closed';
		else
			return 'pending';
	}

    /**
      * Get deltaSign depending upon plannedDate and reference date
      *
      * @param Object $tnaItem
      * @reutrn boolean
     */
    private function getDeltaSign($tnaItem)
    {
        if($this->getTNAItemStatus($tnaItem) === "closed"){
            if($tnaItem->planned_date >= $tnaItem->actual_date){
                return true;
            }
        }

        if($tnaItem->planned_date > $tnaItem->projected_date) {
            return true;
        }

        return false;
    }


    private function calculateDeltaSign($date1, $date2)
    {
        $date1 = Carbon::parse($date1);
        $date2 = Carbon::parse($date2);
        if($date1 < $date2) {
            return false;
        }
        return true;
    }

	private function calculateDeltaWithSign($tnaItem)
	{
        if($this->deltaSign && $this->delta == 0){
            return ''.$this->delta;
        } elseif ($this->deltaSign && $this->delta !== 0) {
            return '-'.$this->delta;
        } else {
            return '+'.$this->delta;
        }
	}

}
