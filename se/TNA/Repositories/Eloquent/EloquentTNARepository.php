<?php

namespace Platform\TNA\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\TNA\Handlers\Console\TNAHealthCalculator;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Models\TNA;
use Platform\TNA\Models\TNAItemPreset;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\Tasks\Helpers\TaskHelper;

class EloquentTNARepository extends Repository implements TNARepository
{
	/**
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
	 * @param TaskHelper $taskHelper
	 */
	public function __construct(TaskHelper $taskHelper)
	{
		$this->taskHelper = $taskHelper;
		parent::__construct(new App, new Collection, new Request);
	}

	/**
	 * @return string [Namespace of Model]
	 */
	public function model(){
		return 'Platform\TNA\Models\TNA';
	}

	/**
	 * Get TNA by id
	 *
	 * @param  UUID $id
	 * @return Object TNA
	 */
	public function getById($id)
	{
		return $this->model->find($id);
	}

	/**
	 * Get all TNA by pagination
	 *
	 * @return Paginated Collection
	 */
	public function getAll($item = 15)
	{
		return $this->model->whereNull('archived_at')->orderBy('updated_at', 'DESC')->paginate($item);
	}

	/**
	 * Get all archived TNA by pagination
	 *
	 * @return Paginated Collection
	 */
	public function getAllArchived($item = 15)
	{
		return $this->model->whereNotNull('archived_at')->orderBy('updated_at', 'DESC')->paginate($item);
	}

	/**
	 * Get TNA according to its type
	 *
	 * @param  string $type
	 * @return Collection
	 */
	public function getByType($type)
	{
		return $this->model->where('tna_state_id', '=', TNAHelper::getTNAStateId($type))->get();
	}

	/**
	 * Create TNA
	 * @param  array $data
	 * @param  Object $user
	 * @return TNA Model
	 */
	public function createTNA($data, $user)
	{
		try{
			$dbData = [
				'id' => $this->generateUUID(),
				'title' => $data['title'],
				'creator_id' => $user->id,
				'order_id' => $data['orderId'],
				'techpack_id' => $data['techpackId'],
				'customer_id' => $data['customerId'],
				'start_date' => $data['startDate'],
				'target_date' => $data['targetDate'],
				'projected_date' => $data['projectedDate'],
				'is_published' => false,
				'tna_state_id' => TNAHelper::getTNAStateId('draft'),
				'customer_name' => $data['customerName'],
				'customer_code' => $data['customerCode'],
				'order_quantity' => $data['orderQuantity'],
				'style_id' => $data['styleId'],
				'style_range' => $data['styleRange'],
				'style_description' => $data['styleDescription'],
				'representor_id' => $data['representor'],
				'tna_health_id' => TNAHelper::getTNAHealthId('normal'),
				'items_order' => $data['itemsOrder']
			];

			\DB::beginTransaction();
			$tna = $this->create($dbData);
			\DB::commit();
			return $tna;
		}
		catch(Exception $e){
			throw new SeException('Something went wrong. Please try again', 500, 50000);
		}
	}

	/**
	 * Update TNA
	 *
	 * @param  array $data
	 * @return TNA Model
	 */
	public function updateTNA($data)
	{
		try{
			$dbData = [
				'title' => $data['title'],
				'start_date' => $data['startDate'],
				'target_date' => $data['targetDate'],
				'customer_id' => $data['customerId'],
				'techpack_id' => $data['techpackId'],
				'order_id' => $data['orderId'],
				'customer_name' => $data['customerName'],
				'customer_code' => $data['customerCode'],
				'order_quantity' => $data['orderQuantity'],
				'style_id' => $data['styleId'],
				'style_range' => $data['styleRange'],
				'style_description' => $data['styleDescription'],
				'representor_id' => $data['representorId'],
				'start_id' => $data['startDate'],
				'target_date' => $data['targetDate'],
				'items_order' => json_encode($data['itemsOrder'])
			];

			\DB::beginTransaction();
			$this->update($dbData, $data['tnaId']);
			$tna = $this->getById($data['tnaId']);
			$this->updateTasks($tna);
			\DB::commit();
			return $tna;
		}
		catch(Exception $e){
			throw new SeException('Something went wrong. Please try again', 500, 50000);
		}
	}

	/**
	 * Delete TNA By id
	 *
	 * @param  UUID $id
	 * @return integer
	 */
	public function deleteTNA($id)
	{
		return $this->delete($id);
	}

	/**
	 * Archive TNA By id
	 *
	 * @param  UUID $id
	 * @return integer
	 */
	public function archiveTNA($id)
	{
		return $this->archive($id);
	}

	/**
	 * Change State of TNA
	 *
	 * @param array $data
	 * @return TNA Model
	 */
	public function changeState($data)
	{
		try{
			$dbData = [
				'tna_state_id' => TNAHelper::getTNAStateId(strtolower($data['tnaState'])),
				'is_published' => $data['isPublished']
			];

            if($data['tnaState'] === 'active') {
                $dbData['published_date'] = Carbon::now();
                $dbData['is_publishing'] = true;
            }

            if($data['tnaState'] === 'completed') {
                $dbData['completed_date'] = Carbon::now();
            }

			$this->update($dbData, $data['tnaId']);
			return $this->getById($data['tnaId']);
		}
		catch(Exception $e){
			throw new SeException('Something went wrong. Please try again', 500, 50000);
		}
	}

	/**
	 * Save itemsOrder for synchronization
	 *
	 * @param  array $data
	 * @return TNA Model
	 */
	public function sync($data)
	{
		$dbData = [
			'items_order' => json_encode($data['itemsOrder'])
		];

		$this->update($dbData, $data['tnaId']);
		return $this->getById($data['tnaId']);
	}

	/**
	 * Complete TNA
	 *
	 * @param  UUID $tnaId
	 * @return integer
	 */
	public function completeTNA($tnaId)
	{
		$dbData = [
			'tna_state_id' => TNAHelper::getTNAStateId('completed'),
			'completed_date' => Carbon::now()
		];

		return $this->update($dbData, $tnaId);
	}

	/**
	 * Add attachment to TNA
	 *
	 * @param array $data
	 * @return  TNA Model
	 */
	public function addAttachment($data)
	{
		$dbData = [
			'attachment' => json_encode($data['attachment'])
		];

		$this->update($dbData, $data['tnaId']);
		return $this->getById($data['tnaId']);
	}

	/**
	 * Add TNA item to itemsOrder
	 *
	 * @param TNAItem Json $transformedItem
	 * @param UUID $tnaId
	 */
	public function addItemOrder($transformedItem, $tnaId)
	{
		$tna = $this->getById($tnaId);
		$itemsOrder = json_decode($tna->items_order);
		$itemsOrder[count($itemsOrder)] = $transformedItem;
		$tna->items_order = json_encode($itemsOrder);
		$tna->projected_date = $transformedItem->plannedDate;
		$tna = (new TNAHealthCalculator)->calculate($tna);
		if($tna->save()){
			return $tna;
		}
		else{
			throw new SeException('Cannot add itemOrder', 422, 4220422);
		}
	}

	/**
	 * Update Tasks tags/creator
	 *
	 * @param  TNA Model $tna
	 * @return
	 */
	public function updateTasks($tna)
	{
		$tnaItems = $tna->items;
		$tags = TNAHelper::getTagsForTask($tna);
		$tnaItems->each(function($item, $key) use($tna, $tags) {
			if(!is_null($item->task)){
				$item->task->creator_id = $tna->representor_id;
				$item->task->tags()->sync($this->taskHelper->changeToTagId($tags));
				$item->task->save();
			}
		});
	}

	/**
	 * Delete Attachment of TNA
	 *
	 * @param  UUID $tnaId
	 * @return integer
	 */
	public function deleteAttachment($tnaId)
	{
		$dbData = [
			'attachment' => NULL
		];
		return $this->update($dbData, $tnaId);
	}

	/**
	 * Search TNA
	 * @param  array $data
	 * @return mixed
	 */
	public function search($data)
	{
		if (key($data) == 'start_date' ||
			key($data) == 'target_date' ||
			key($data) == 'published_date' ||
			key($data) == 'projected_date' ||
			key($data) == 'completed_date'
		){
            $date = Carbon::parse($data[key($data)])->toDateString();
            return $this->model
                    ->whereBetween(key($data), [$date.' 00:00:00', $date.' 23:59:59'])
                    ->paginate($data['item']);
        }
        if (key($data) == 'customer_name' ||
        	key($data) == 'title'
        ){
        	return $this->model
        			->where(key($data), 'ILIKE', '%'.$data[key($data)].'%')
                    ->paginate($data['item']);
        }

        return $this->model
        			->where(key($data), '=', $data[key($data)])
        			->paginate($data['item']);
	}

	public function getCategorySchema()
	{
		return \Platform\TNA\Models\TNAItemDepartment::orderBy('id')->lists('department');
	}

	/**
	 * @param  array $data
	 * @return mixed
	 */
	public function filterTna($data)
	{
		$item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
		return $this->filter($data)->paginate($item);
	}

    /**
     * Get tna list to be published or dispatched
     * 
     * @return Collection
     */
    public function getTNAToBePublished()
    {
        return $this->model->where('is_publishing', true)->get();
    }

    /**
    * Get All Calendar created today 
     * This method is getting called for sending digest notification
	 * @param  string  $id
	 * @return mixed
	 */
	public function getTodayCreatedTNAList($id){
		$calendar = $this->model->where('creator_id',$id)
							->whereNULL('archived_at')
							->whereNULL('deleted_at')
							->where('created_at','>', Carbon::today())
							->get()
							->toArray();
		return $calendar;
	}

}
