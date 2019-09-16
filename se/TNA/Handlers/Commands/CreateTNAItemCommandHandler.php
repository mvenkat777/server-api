<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Commands\SaveTemplateCommand;
use Platform\TNA\Commands\PublishTnaItemCommand;
use Platform\TNA\Helpers\TNAPublisher;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;
use Platform\TNA\Repositories\Contracts\TNATemplateRepository;

class CreateTNAItemCommandHandler implements CommandHandler 
{
    const DEFAULT_DEPARTMENT_ID = 1;

    const DEFAULT_VISIBILITY = 1;

    const FIRST_TEMPLATE_COUNT = 1;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepo;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

    /**
     * @var Platform\TNA\Helpers\TNAPublisher
     */
    protected $tnaPublisher;

    /**
     * @var Platform\TNA\Repositories\Contracts\TNATemplateRepository;
     */
    protected $tnaTemplateRepository;

	/**
	 * @param TNAItemRepository   $tnaItemRepo         
	 */
	public function __construct(
        TNAItemRepository $tnaItemRepo,
        TNATemplateRepository $tnaTemplateRepository,
        TNAPublisher      $tnaPublisher,
		DefaultCommandBus $commandBus)
	{
		$this->tnaItemRepo = $tnaItemRepo;
		$this->commandBus = $commandBus;
        $this->tnaPublisher = $tnaPublisher;
        $this->tnaTemplateRepository = $tnaTemplateRepository;
	}

	/**
	 * @param  CreateTNACommand $command 
	 * @return TNAItem          
	 */
	public function handle($command)
	{
        $representor = TNAHelper::getUserIdByEmail($command->representor, true);
        $command->representor = $representor ?
                                 $representor : $command->tna->representor_id;

		\DB::beginTransaction();
		$tnaItem = $this->tnaItemRepo->createItem((array)$command);

        if($tnaItem->is_milestone) {
            $tnaItem->itemsOrder = $this->createNodes($command->nodes, $tnaItem);
            $tnaItem->tna = (new TNAProjectedDateCalculator)->calculate($tnaItem->tna, $tnaItem->itemsOrder);

            if(!is_null($command->templateId)) {
                $this->tnaTemplateRepository->updateTemplateCount($command->templateId);
            }
        }

        if($command->saveTemplate) {
            $this->commandBus->execute(new SaveTemplateCommand(TNAHelper::transformToTemplateData($command, self::FIRST_TEMPLATE_COUNT)));
        }

        if($command->tna->state->state === 'active') {
            if($tnaItem->is_milestone) {
                $tna = $this->tnaPublisher->publish($tnaItem->tna()->first());
                $tnaItem->itemsOrder = json_decode($tna->items_order);
            } else {
                $tnaItem->itemsOrder = $this->commandBus->execute(new PublishTnaItemCommand($tnaItem->id, $command->tna, $tnaItem));
            }
        }

        /*
        if($command->doSync){
            $tnaItem->itemsOrder = $this->updateItemsOrder($tnaItem, $command->tna,  $command->tna->items_order);
        }
         */

		\DB::commit();
		return $tnaItem;
	}

    private function createNodes($nodes, $milestone)
    {
        $itemsOrder = json_decode($milestone->tna->items_order);
        foreach($nodes as $node) {
            $node['dependorId'] = $milestone->id;
            $node['description'] = isset($node['description']) ? $node['description'] : "";
            $node['isMilestone'] = false;
            $node['visibility'] = [self::DEFAULT_VISIBILITY];
            $node['departmentId'] = self::DEFAULT_DEPARTMENT_ID;
            $node['tnaId'] = $milestone->tna_id;
            $node['representor'] = TNAHelper::getUserIdByEmail($node['representor'], true) 
                                ? TNAHelper::getUserIdByEmail($node['representor'])
                                : $milestone->representor_id;  
            $node['creatorId'] = $milestone->creator_id;
            $tnaItem = $this->tnaItemRepo->createItem($node);
            $itemsOrder = $tnaItem->itemsOrder;
        }
        return $itemsOrder;
    }

    /**
     * Add item to items order and save itemsorder
     *
     * @var object $tnaItem
     * @var object $tna
     * @var string $itemsOrder
     * @return json array
     */
    private function updateItemsOrder($tnaItem, $tna, $itemsOrder)
    {
        if(is_null($tnaItem->dependor_id)){
            $itemsOrder = $this->addToItemsOrder($tnaItem, $itemsOrder);
        } else{
            $itemsOrder = $this->addToItemInItemsOrder($tnaItem, $itemsOrder);
        }

        $tnaItem->tna->items_order = json_encode($itemsOrder);
        $tnaItem->tna->save();

        return $itemsOrder;
    }

    /**
     * Add newly created item to itemsOrder
     *
     * @var object $tnaItem
     * @var jsonarray $itemsOrder
     * @return sorted json array
     */
    private function addToItemsOrder($tnaItem, $itemsOrder)
    {
       $itemsOrder = json_decode($itemsOrder, true); 

       /*
       if(count($itemsOrder) < 1){
            $itemsOrder[0] = (array)TNAHelper::getTransformedItem($tnaItem);
       } else {
            $itemsOrder[] = (array)TNAHelper::getTransformedItem($tnaItem);
       }
*/
       $itemsOrder[] = (array)TNAHelper::getTransformedItem($tnaItem);

       return $itemsOrder;
       return TNAHelper::sortItemsOrder($itemsOrder);
    }

    /**
     * Add newly created item to nodes in itemsOrder
     *
     * @var object $tnaItem
     * @var jsonarray $itemsOrder
     * @return json array
     */
    private function addToItemInItemsOrder($tnaItem, $itemsOrder)
    {
       $itemsOrder = json_decode($itemsOrder, true); 
       $foundKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
       if($foundKey !== null){
            $itemsOrder[$foundKey]['nodes'][] = (array)TNAHelper::getTransformedItem($tnaItem);
            //$itemsOrder[$foundKey]['nodes'] = TNAHelper::sortItemsOrder($itemsOrder[$foundKey]['nodes']);
       }
       return $itemsOrder;
    }

}
