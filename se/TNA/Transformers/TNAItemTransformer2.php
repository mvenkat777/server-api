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
use App\User;

class TNAItemTransformer2 extends TNATransformerAbstract 
{
	protected $delta;

	protected $deltaSign;

    protected $projectedDate;

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

		// $week = TNAHelper::getWeek($tnaItem);
		$this->calculateDelta($tnaItem);

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
			'itemStatus' => $tnaItem->tem_status_id,
			'visibility' => $visibility['data'],
			'tnaId' => $tnaItem->tna_id,
			'nodes' => [],
			'itemStatus' => $this->getTNAItemStatus($tnaItem),
			'delta' => $this->calculateDeltaWithSign($tnaItem),
			'deltaSign' => $this->deltaSign,
			'projectedDate' => $this->projectedDate,
            'deltaValue' => $this->delta,
            'department' => $tnaItem->department,
			// 'week' => $week,
			'task' => $task['data'],
			'createdAt' => $tnaItem->created_at->toDateTimeString(),
			'updatedAt' => $tnaItem->updated_at->toDateTimeString()
		];

        return $transformedData;
    }
}
