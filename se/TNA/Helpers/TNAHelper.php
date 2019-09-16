<?php
namespace Platform\TNA\Helpers;

use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Models\TNAItem;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Transformers\TNAItemTransformer;

class TNAHelper
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @param TNARepository $tnaRepository 
	 */
	function __construct(TNARepository $tnaRepository) {
		$this->tnaRepository = $tnaRepository;
	}

	/**
	 * Get id of TNA state by giving state name
	 * @param  TNAState $state 
	 * @return integer        
	 */
	public static function getTNAStateId($state)
	{
		$tnaState = \Platform\TNA\Models\TNAState::where('state', '=', $state)->first();
		if($tnaState)
			return $tnaState->id;
		else
			throw new SeException("Invalid TNA State", 422, 4200100);
	}

    /**
     * Get TNA department id according to given department name
     *
     * @param  string $department 
     * @return integer             
     */
    public static function getTNADepartmentId($department)
    {
        $tnaDepartment = \Platform\TNA\Models\TNAItemDepartment::where('department', '=', $department)->first();
        if($tnaDepartment)
            return $tnaDepartment->id;
        else
            throw new SeException("Invalid TNA Department", 422, 4200100);
    }

	/**
	 * For getting the id if tna health
	 * 	
	 * @param  string $health 
	 * @return integer         
	 */
	public static function getTNAHealthId($health)
	{
		$tnaHealth = \Platform\TNA\Models\TNAHealth::where('health', '=', $health)->first();
		if($tnaHealth)
			return $tnaHealth->id;
		else
			throw new SeException("Invalid TNA State", 422, 4200100);
	}

	/**
	 * Get array of id of visibility of tna item
	 * (Used in UpdateTNAItemCommandHandler)
	 * @param  array  $visibility 
	 * @return array             
	 */
	public static function getVisibilityIdArray(array $visibility)
	{
		return array_column($visibility, 'id');
	}

	/**
	 * Get the tna state name according to action
	 * @param  string $state 
	 * @return string        
	 */
	public static function convertToDbState($state)
	{
		$stateArray = [
			'unpublish' => 'draft',
			'publish' => 'active',
			'pause' => 'paused',
			'resume' => 'active',
			'archive' => 'completed'
		];
		$result = $stateArray[strtolower($state)];
		if(isset($result))
			return $result;
		else
			throw new SeException("Invalid TNA State Provided", 422, 4200100);
	}

	/**
	 * Get User Id by providing Email
	 * @param  string/email $email 
	 * @return string/UUID        
	 */
	public static function getUserIdByEmail($email, $skipException = false)
	{
		$user = \App\User::where('email', '=', $email)->first();

		if($user){
			return $user->id;
        } else {
            if($skipException) {
                return false;
            }
			throw new SeException('User does not exist', 404, 4200404);
        }
	}

	/**
	 * Add no. of days to given date
	 * @param Date $date 
	 * @param date $day  
	 */
	public static function addDayToDate($date, $day)
	{
		return Carbon::parse($date)->addDays($day);
	}

    public static function substractDayFromDate($date, $day)
    {
        return Carbon::parse($date)->subDays($day);
    }

	/**
	 * For getting the transformed tnaItem before going to controller
	 * @param  TNAItem $tnaItem 
	 * @return JSON          
	 */
	public static function getTransformedItem($tnaItem)
	{
		return json_decode(json_encode((new TNAItemTransformer())->transform($tnaItem)));
	}

    /**
     * Update items_order in TNA after updating the TNA item
     * @param  Object $tnaItem 
     * @param  string/UUID $tnaId   
     * @return array          
     */
	public static function updateOneItemOrder(TNAItem $tnaItem, $itemsOrder = null)
	{
        if(is_null($itemsOrder)){
            $itemsOrder = json_decode($tnaItem->tna->items_order, true);
        }

        if(is_null($tnaItem->dependor_id)) {
            $foundKey = array_search($tnaItem->id, array_column($itemsOrder, 'itemId'));
            if($foundKey !== false) {
                $nodes = $itemsOrder[$foundKey]['nodes'];
                $itemsOrder[$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
                $itemsOrder[$foundKey]['nodes'] = $nodes;
            }
        } else {
            $parentKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
            if($parentKey !== null) {
                $foundKey = array_search($tnaItem->id, array_column($itemsOrder[$parentKey]['nodes'], 'itemId'));
                if($foundKey !== false) {
                    $nodes = $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'];
                    $itemsOrder[$parentKey]['nodes'][$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
                    $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'] = $nodes;
                }
            }
        }
        return $itemsOrder;
	}

    public static function calculateTaskDays($tna, $itemsOrder, $key)
    {
        if ($key === 0) {
            return TNAHelper::diffInDates(TNAHelper::getReferenceDate($tna), $itemsOrder[$key]->plannedDate);
        }
        return TNAHelper::diffInDates($itemsOrder[$key - 1]->plannedDate, $itemsOrder[$key]->plannedDate);
    }

	/**
	 * Calculate the week of tna task
	 * 
	 * @param TNAItem $tnaItem 
	 * @return integer          
	 */
	public static function getWeek($tnaItem)
	{
		$tna = $tnaItem->tna;
		$targetDate = Carbon::parse($tna->target_date);
		$startDate = Carbon::parse($tna->start_date);
		$plannedDate = Carbon::parse($tnaItem->planned_date);
		$totalWeek = ceil(($targetDate->diffInDays($startDate))/7);
		if($totalWeek < 1){
			return 1;
		}
		$i = 1;
		while($totalWeek >= $i){
			$compareDate = $startDate->addDays(7);
			// echo $i;
			// echo $compareDate;
			if($plannedDate < $compareDate){
				// dd($plannedDate, $compareDate, $totalWeek - $i);
				return $totalWeek - $i+1;
			}
			$i++;
		}
	}

	/**
	 * Get different between planned date and task dueDate
	 * 
	 * @param  date $taskDueDate    
	 * @param  date $tnaPlannedDate 
	 * @return integer                 
	 */
	public function getTaskDays($taskDueDate, $tnaPlannedDate)
	{
		$taskDueDate = Carbon::parse($taskDueDate);
		$tnaPlannedDate = Carbon::parse($tnaPlannedDate);
		return $tnaPlannedDate - $taskDueDate;
	}

	/**
	 * Get array needed for creating new item
	 * 
	 * @param  TNAItem $item 
	 * @param  TNA $tna  
	 * @param  array $departments  
	 * @return array       
	 */
	public static function convertForItemCommand($presetItem, $tna, $departments, $itemDepartments) 
    {
        return [
			'title' => $presetItem->title,
			'description' => $presetItem->description,
            'representor' => array_key_exists($itemDepartments[$presetItem->department_id], $departments) 
                                && !empty($departments[$itemDepartments[$presetItem->department_id]])
                                ? $departments[$itemDepartments[$presetItem->department_id]] : $tna->representor->email,
			'taskDays' => $presetItem->task_days,
			'isMilestone' => $presetItem->is_milestone,
            'isParallel' => $presetItem->is_parallel,
            'departmentId' => $presetItem->department_id,
            'plannedDate' => $tna->target_date,
            'creatorId' => $tna->creator_id,
            'doSync' => false,
            'skipCheck' => true
		];
	}

	/**
	 * Check if data in an array is not empty
	 * 
	 * @param  array   $data     
	 * @param  string  $variable 
	 * @return boolean           
	 */
	public static function isSetAndIsNotEmpty(array $data, $variable)
	{
		return isset($data[$variable]) && !empty($data[$variable]);
	}

	/**
	 * Get tags title for creating task
	 * 
	 * @param  TNA $tna 
	 * @return array      
	 */
	public static function getTagsForTask($tna)
	{
		$tags = [];
 		if(isset($tna->customer_name) && !is_null($tna->customer_name) && ($tna->customer_name !== "")) { array_push($tags, $tna->customer_name); }
 		if(isset($tna->customer_code) && !is_null($tna->customer_code) && ($tna->customer_code !== "")) { array_push($tags, $tna->customer_code); }
 		if(isset($tna->order_id) && !is_null($tna->order_id) && ($tna->order_id !== "")) { array_push($tags, $tna->order_id); }
 		if(isset($tna->style_id) && !is_null($tna->style_id) && ($tna->style_id !== "")) { array_push($tags, $tna->style_id); }
 		if(isset($tna->style_range) && !is_null($tna->style_range) && ($tna->style_range !== "")) { array_push($tags, $tna->style_range); }
 		if(isset($tna->vendor_id) && !is_null($tna->vendor_id) && ($tna->vendor_id !== "")) { array_push($tags, $tna->vendor_id); }
 					
 		return $tags;
	}

	/**
	 * Give the different between two dates
	 * 
	 * @param  DATE $firstDate  
	 * @param  DATE $secondDate 
	 * @param  string $type 
	 * @return integer             
	 */
	public static function diffInDates($firstDate, $secondDate, $type='days')
	{
        $firstDate = Carbon::parse(Carbon::parse($firstDate)->toDateString());
        $secondDate = Carbon::parse(Carbon::parse($secondDate)->toDateString());
		if($type === 'days'){
			return $firstDate->diffInDays($secondDate);
		}
	}

	/**
	 * Give the different between two dates but can give -ve number also
	 * 
	 * @param  DATE $firstDate  
	 * @param  DATE $secondDate 
	 * @param  string $type 
	 * @return integer             
	 */
	public static function diffInDatesAsRealNum($firstDate, $secondDate, $type='days')
	{
        $firstDate = Carbon::parse(Carbon::parse($firstDate)->toDateString());
        $secondDate = Carbon::parse(Carbon::parse($secondDate)->toDateString());
		if($type === 'days'){
			return $firstDate->diffInDays($secondDate, false);
		}
	}

	/**
	 * Transform the given field to database fields
	 * 
	 * @param  array $data 
	 * @return array     
	 */
	public static function transform($data)
    {
        $key = key($data);
        $schema = [
        	'title' => 'title',
        	'customer' => 'customer_name',
            'state' => 'tna_state_id',
            'owner' => 'representor_id',
        	'startDate' => 'start_date',
        	'targetDate' => 'target_date',
        	'publishedDate' => 'published_date',
        	'projectedDate' => 'projected_date',
        	'completedDate' => 'completed_date',
        	'tnaHealth' => 'tna_health_id'
        ];
        if(!array_key_exists($key, $schema)){
            throw new SeException("Error Processing Request", 422, 0104);
        }
        if ($schema[$key] == 'tna_state_id') {
        	return [$schema[$key]=> self::getTNAStateId($data[$key])];
        }
        if ($schema[$key] == 'tnaHealth') {
        	return [$schema[$key]=> self::getTNAHealthId($data[$key])];
        }
        return [$schema[$key]=> $data[$key]];
    }

    public static function sortItemsOrder($itemsOrder, $isObject = false)
    {
        if($isObject){
            usort($itemsOrder, function($a, $b) { //Sort the array using a user defined function
                return $a->plannedDate > $b->plannedDate ? 1 : -1; //Compare the scores
            });                                                                                                                                                                                                        
            return $itemsOrder;
        }

        $itemsOrder = (array)$itemsOrder;
        usort($itemsOrder, function($a, $b) { //Sort the array using a user defined function
            return $a['plannedDate'] > $b['plannedDate'] ? 1 : -1; //Compare the scores
        });                                                                                                                                                                                                        
        return $itemsOrder;
    }

    public static function getReferenceDate($tna)
    {
        if($tna->state->state == 'draft'){
            return $tna->start_date;
        }
        return $tna->published_date;
    }

    public static function transformToTemplateData($command, $count = 0)
    {
        $data = [
            'title' => $command->title,
            'description' => $command->description,
            'plannedDate' => null,
            'isMilestone' => true,
            'representor' => null,
            'nodes' => []
        ];

        foreach($command->nodes as $node) {
            $data['nodes'][] = [
                'title' => $node['title'],
                'description' => isset($node['description']) ? $node['description'] : $node['title'],
                'plannedDate' => null,
                'isMilestone' => false,
                'representor' => null,
            ];
        }

        return [
            'title' => $command->title,
            'description' => $command->description,
            'creatorId' => $command->creatorId,
            'isMilestoneTemplate' => true,
            'data' => $data,
            'count' => $count
        ];
    }

}
