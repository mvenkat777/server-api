<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Commands\CreateTNAItemCommand;
use Platform\TNA\Commands\SyncCommand;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Transformers\TNAItemTransformer;
use Platform\TNA\Validators\TNACommandValidator;
use Platform\TNA\Commands\CreateItemsFromPresetCommand;
use Platform\TNA\Models\TNAItemPreset;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;
use Platform\TNA\Transformers\TNATransformer;
use Rhumsaa\Uuid\Uuid;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;

class CreateTNACommandHandler extends TNACommandValidator implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepository;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

    /**
     * url = https://docs.google.com/spreadsheets/d/1SkZHeZEHmRIarlE3w8crUyNudbjac7glGaNJdhejFZY/edit#gid=1581729594
     */
    private $milestones = [
                'VLP CREATED' => [14, false, '2016-07-02 00:00:00', 2],
                'VLP APPROVED' => [7, true, '2016-07-03 00:00:00', 1],
                'FIRST PROTO SENT' => [28, true, '2016-07-04 00:00:00', 2],
                'SECOND PROTO SENT' => [14, false, '2016-07-05 00:00:00', 2],
                'FABRICS/MATERIAL Approved' => [0, false, '2016-07-06 00:00:00', 2],
                'PROTO APPROVED' => [7, false, '2016-07-07 00:00:00', 2],
                'CUSTOMER PO & DEPOSIT RECEIVED' => [7, false, '2016-07-07 00:00:00', 1],
                'PP SAMPLE APPROVED' => [28, false, '2016-07-07 00:00:00', 3],
                'PRODUCTION DONE' => [35, false, '2016-07-07 00:00:00', 4],
                'GARMENT DELIVERED' => [7, false, '2016-07-08 00:00:00', 4] 
            ];

	/**
	 * @param DefaultCommandBus $commandBus    
	 * @param TNARepository     $tnaRepository 
	 * @param TNAItemRepository     $tnaItemRepository 
	 */
	public function __construct(
		DefaultCommandBus $commandBus,
		TNARepository $tnaRepository,
		TNAItemRepository $tnaItemRepository
	) {
		$this->commandBus = $commandBus;
		$this->tnaRepository = $tnaRepository;
        $this->tnaItemRepository = $tnaItemRepository;
	}

	/**
	 * @param  CreateTNACommand $command 
	 * @return TNA          
	 */
	public function handle($command)
	{
		// if($this->isLessThanToday($command->targetDate)){
		// 	throw new SeException('Target Date is Less than Today', 422, 4200101);
		// }

		if($command->targetDate < $command->startDate){
			throw new SeException('Target Date Cannot be less than Start Date', 422, 4200101);
		}
		$command->representor = TNAHelper::getUserIdByEmail($command->representor);

		\DB::beginTransaction();
		$tna = $this->tnaRepository->createTNA((array)$command, \Auth::user());

        if($command->wantCloning && !is_null($command->cloningTnaId)) {
            return $this->cloneTNA($tna, $command);
            //return (new TNAProjectedDateCalculator)->calculate($this->cloneTNA($command));
        } else if($command->isCreateTemplate){
			$this->createTnaItemsFromPreset($tna , $command->departments);
        } 
        /*
         else {
            $this->createTNAItemMilestones($tna);
        }
         */
		\DB::commit();

		return (new TNAProjectedDateCalculator)->calculate($tna);
	}

    /**
     * Create default milestones from preset
     *
     * @var object $tna
     */
    public function createTNAItemMilestones($tna)
    {
        $representor = $tna->representor->email; 
        $itemsOrder = [];
        $prevPlannedDate = $tna->start_date;

        foreach($this->milestones as $title => $milestone) {
            $data = $this->getMilestoneData($tna, $milestone, $representor, $title);

            $data['plannedDate'] = TNAHelper::addDayToDate($prevPlannedDate, $milestone[0]);
            $prevPlannedDate = $data['plannedDate'];

		    $tnaItem = $this->commandBus->execute(new CreateTNAItemCommand($data, $tna->id, $tna));
            //$itemsOrder[] = (new TNAItemTransformer)->tranform($tnaItem);
        }

        /*
        $itemsOrder = (new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tna->id);
		$itemsOrder = $this->commandBus->execute(new SyncCommand($itemsOrder, $tna->id));
         */
        /*
		$tna->items_order = json_encode($itemsOrder);
        $tna->is_creating_preset = false;
		if(!$tna->save()){
			throw new SeException('Unable to create TNA items', 500, 50000);
		}
         */
    }

	/**
	 * Create TNA items from Preset
	 * 
	 * @param  TNA Model $tna        
	 * @param  [array] $deparments [description]
	 */
	private function createTnaItemsFromPreset($tna, $departments)
	{
        $tna->is_creating_preset = true;
        $tna->save();
        $data = json_encode([
            'tnaId' => $tna->id,
            'departments' => $departments,
            'creatorId' => $tna->creator_id
        ]);
        \DB::insert("INSERT INTO tna_create_preset (tna_id, data) VALUES ('$tna->id', '$data')");
        return;
	}

    private function cloneTNA($clonedTna, $command)
    {
        $cloningTna = $this->tnaRepository->getById($command->cloningTnaId);
        if($cloningTna) {
            /*
            $tnaData = $this->getTNAData($tna);
            $clonedTna = $this->tnaRepository->createTNA($tnaData, \Auth::user());
             */

            $itemsOrder = json_decode($cloningTna->items_order);
            foreach($itemsOrder as $item) {
                $itemData = $this->getItemData($item, $clonedTna);
                $clonedItem = $this->tnaItemRepository->createItem($itemData);
                foreach($item->nodes as $node) {
                    $nodeData = $this->getItemData($node, $clonedTna, $clonedItem->id);
                    $clonedNode = $this->tnaItemRepository->createItem($nodeData);
                }
            }
            \DB::commit();
            return $clonedTna->fresh();
        } else {
            throw new SeException('Calender to be cloned is not found', 404, 4200132);
        }
    }

    /**
     * Retrun the data required for creating tna item in repository
     *
     * @param   Model   $item
     * @param   Model   $clonedTna
     * @return  Array
     */
    private function getItemData($item, $clonedTna, $dependorId = null)
    {
        $departmentId = is_null($item->department) ? $item->department : TNAHelper::getTNADepartmentId($item->department);
        return [
			'title' => $item->title,
			'description' => $item->description,
			'tnaId' => $clonedTna->id,
			'creatorId' => \Auth::user()->id,
			'plannedDate' => $item->plannedDate,
			'representor' => $item->representor->id,
			'dependorId' => $dependorId,
			'isMilestone' => $item->isMilestone,
            'departmentId' => $departmentId,
            'visibility' => [1]
        ];
    }

    /**
     * Get Data for Milestone to create item
     *
     * @var object $tna
     * @var object $milestone
     * @var email $representor
     * @return array
     */
    public function getMilestoneData($tna, $milestone, $representor, $title)
    {
        return [
            'title' => $title,
            'description' => $title,
            'representor' => $representor,
            'taskDays' => $milestone[0],
            'isMilestone' => true,
            'departmentId' => $milestone[3],
            'plannedDate' => $milestone[2],
            'creatorId' => $tna->creator_id,
            'doSync' => false,
            'skipCheck' => true
        ];
    }

}
