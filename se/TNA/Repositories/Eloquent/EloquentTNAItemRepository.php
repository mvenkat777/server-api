<?php

namespace Platform\TNA\Repositories\Eloquent;

use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\TNA\Models\TNAItem;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Helpers\TNAHelper;

class EloquentTNAItemRepository extends Repository implements TNAItemRepository 
{

	/**
	 * Get Namespace of Model
	 * @return string 
	 */
	public function model(){
		return 'Platform\TNA\Models\TNAItem';
	}

	/**
	 * Get TNA item by id
	 * 
	 * @param  UUID $id 
	 * @return TNAItem Model     
	 */
	public function getById($id)
	{
		return $this->model->find($id);
	}

	/**
	 * Get all items of a TNA 
	 * 
	 * @param  UUID $tnaId 
	 * @return Collection        
	 */
	public function getByTNAId($tnaId)
	{
		return $this->model->where('tna_id', '=', $tnaId)
							->orderBy('updated_at', 'DESC')
							->get();
	}

	/**
	 * Get all Dispatched Items of a TNA
	 * 
	 * @param  UUID $tnaId 
	 * @return Collection        
	 */
	public function getDispatchedItems($tnaId)
	{
		return $this->model->where('tna_id', '=', $tnaId)
							->where('is_dispatched', '=', true)
							->get();
	}

	/**
	 * Create TNA item
	 * 
	 * @param  array $data 
	 * @param  User Model $user 
	 * @return TNAItem Model       
	 */
	public function createItem($data)
	{
		$dbData = [
			'id' => $this->generateUUID(),
			'title' => $data['title'],
			'description' => $data['description'],
			'tna_id' => $data['tnaId'],
			'creator_id' => $data['creatorId'],
			//'task_days' => $data['taskDays'],
			'planned_date' => $data['plannedDate'],
			'representor_id' => $data['representor'],
			'dependor_id' => $data['dependorId'],
			'is_milestone' => $data['isMilestone'],
			'department_id' => $data['departmentId'],
            //'is_parallel' => $data['isParallel'],
            //'projected_date' => $data['plannedDate'],
            //'delta' => '0',
            //'is_priority_task' => $data['isPriorityTask']
		];

		\DB::beginTransaction();
		$tnaItem = $this->create($dbData);
        $tnaItem->itemsOrder = $this->addToItemsOrder($tnaItem);
		$tnaItem->visibility()->sync($data['visibility'], false);
		\DB::commit();
		return $tnaItem;
	}

	/**
	 * Update TNA item
	 * 
	 * @param  array $data 
	 * @return TNAItem Model       
	 */
	public function updateItem($data)
	{
		$dbData = [
			'title' => $data['title'],
			'description' => $data['description'],
			//'task_days' => $data['taskDays'],
			'planned_date' => $data['plannedDate'],
			'representor_id' => $data['representorId'],
			'dependor_id' => $data['dependorId'],
			'is_milestone' => $data['isMilestone'],
            //'is_parallel' => $data['isParallel'],
            //'projected_date' => $data['projectedDate'],
            //'delta' => $data['delta']
		];

		try{
			\DB::beginTransaction();
			$result = $this->update($dbData, $data['itemId']);
			$tnaItem = $this->getById($data['itemId']);
			//$tnaItem->visibility()->sync($data['visibility'], true);
			\DB::commit();
			return $tnaItem;
		}
		catch(Exception $e){
			throw new SeException("Error while updating tnaItem", 500, 50000);
		}
	}

	/**
	 * Delete TNA item by id
	 * 
	 * @param  UUID $id 
	 * @return integer     
	 */
	public function deleteItem($id)
	{
		\DB::beginTransaction();
		$this->model->find($id)->visibility()->detach();
		$result = $this->delete($id);
		\DB::commit();
		return $result;
	}

	/**
	 * Delete Item by TNA id
	 * 
	 * @param  UUID $tnaId 
	 * @return integer        
	 */
	public function deleteItemByTNA($tnaId)
	{
		return $this->model->where('tna_id', '=', $tnaId)->delete();
	}

    /**
     * Delete all dependent items inside a parent item
     *
     * @param string UUID $dependorId
     * @return integer
     */
    public function deleteDependentItems($dependorId)
    {
        $items = $this->model->with(['task'])->where('dependor_id', $dependorId)->get();

        foreach($items as $item) {
            if(!is_null($item->task)) {
                $item->task->delete();
            }
            $item->delete();
        }
    }

	/**
	 * Update plannedDate and dependorId for item for synchronization
	 * 
	 * @param  array $data 
	 * @return TNAItem Model       
	 */
	public function sync($data)
	{
		$dbData = [
			'planned_date' => $data['plannedDate'],
			'dependor_id' => $data['dependorId']
		];

		try{
			$this->update($dbData, $data['itemId']);
			return $this->getById($data['itemId']);
		}
		catch(Exception $e){
			throw new SeException('Error while updating', 500, 50000);
		}
	}

	/**
	 * Complete TNA Item
	 * 
	 * @param  UUID $itemId 
	 * @return TNAItem Model         
	 */
	public function completeItem($itemId)
	{
		$dbData = [
			'is_dispatched' => true,
			'is_completed' => true
		];

		try{
			$this->update($dbData, $itemId);
			return $this->getById($itemId);
		}
		catch(Exception $e){
			throw new SeException('Error while completing item', 500, 50000);
		}
	}

	/**
	 * Dispatch Item 
	 * 
	 * @param  UUID $itemId 
	 * @param  UUID $taskId 
	 * @return TNAItem Model         
	 */
	public function dispatch($itemId, $taskId)
	{
		$dbData = [
			'is_dispatched' => true,
			'task_id' => $taskId
		];

		try{
			$this->update($dbData, $itemId);
			return $this->getById($itemId);
		}
		catch(Exception $e){
			throw new SeException('Error while disptching item', 500, 50000);
		}
	}

    public function addToItemsOrder($tnaItem)
    {
        $itemsOrder = json_decode($tnaItem->tna->items_order, true);

        if($tnaItem->is_milestone) {
            $itemsOrder[] = (array)TNAHelper::getTransformedItem($tnaItem);
        } else {
            $foundKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
            if($foundKey !== null){
                $itemsOrder[$foundKey]['nodes'][] = (array)TNAHelper::getTransformedItem($tnaItem);
            }
        }

        $tnaItem->tna->items_order = json_encode($itemsOrder);
        $tnaItem->tna->save();

        return $itemsOrder;
    }

	/**
	 * Save Task according to TNA
	 * 
	 * @param  TNAItem Model $tnaItem 
	 * @return Task Model          
	 */
	public function saveTask($tnaItem)
	{
		if(is_null($tnaItem->task)){
			return;
		}
		$task = $tnaItem->task;
		$task->title = $tnaItem->title;
		$task->description = $tnaItem->description;
		$task->due_date = $tnaItem->planned_date;
        $task->assignee_id = $tnaItem->representor_id;
        // update the relationship otherwise it will cause problem in activity by taking old data
        $task = $task->setRelation('assignee', $task->assignee()->first());
		if($task->save()){
			return $task;
        }
	}

}
