<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Models\TNAItem;
use Platform\App\Exceptions\SeException;

class DeleteTNAItemCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepo;

	/**
	 * @param TNAItemRepository $tnaItemRepo 
	 */
	public function __construct(TNAItemRepository $tnaItemRepo)
	{
		$this->tnaItemRepo = $tnaItemRepo;
	}

	/**
	 * @param  DeleteTNAComman $command 
	 * @return integer          [Number of deleted rows]
	 */
	public function handle($command)
	{
		\DB::beginTransaction();
		if(!is_null($command->tnaItem->task)){
			$command->tnaItem->task->delete();
		}

        $itemsOrder = $this->deleteFromItemsOrder($command->tnaItem);
        if(is_null($command->tnaItem->dependor_id)){
            $this->tnaItemRepo->deleteDependentItems($command->tnaItem->id);
        }

		$result = $this->tnaItemRepo->deleteItem($command->itemId);

		\DB::commit();
		return $result;
	}

    /**
     * Delete item or items from itemsOrder of TNA
     *
     * @param Object $tnaItem
     * @return boolean
     */
    private function deleteFromItemsOrder($tnaItem)
    {
        $itemsOrder = json_decode($tnaItem->tna->items_order, true);
        if(is_null($tnaItem->dependor_id)) {
            $foundKey = array_search($tnaItem->id, array_column($itemsOrder, 'itemId'));
            if($foundKey !== null) {
                unset($itemsOrder[$foundKey]);
                $itemsOrder = array_values($itemsOrder);
            }
        } else {
            $parentKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
            if($parentKey !== null){
                 $foundKey = array_search($tnaItem->id, array_column($itemsOrder[$parentKey]['nodes'], 'itemId'));
                 if($foundKey !== null) {
                    unset($itemsOrder[$parentKey]['nodes'][$foundKey]);
                    $itemsOrder[$parentKey]['nodes'] = array_values($itemsOrder[$parentKey]['nodes']);
                 }
            } else {
                throw new SeException("Item order not found", 422, 4200124);
            }
        }

        $tnaItem->tna->items_order = json_encode($itemsOrder);
        if($tnaItem->tna->save()) {
            return true;
        } else {
            throw new SeException("Cannot delete item", 422, 4200126);
        }
    }

}
