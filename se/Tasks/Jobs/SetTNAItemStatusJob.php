<?php

namespace Platform\Tasks\Jobs;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Platform\App\Jobs\Job;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Commands\SyncCommand;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\DefaultCommandBus;


class SetTNAItemStatusJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $task;

    /**
     * 
     * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
     */
    protected $tnaItemRepository;

    /**
     * 
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;
    

    
    /**
     * Create a new job instance.
     *
     * @param  Task  $task
     *         
     * @return void
     */
    public function __construct($task)
    {
        $this->task=$task;

        $this->onQueue('mediumJob');
        
        
    }

    /**
     * Execute the job.
     *
     * @param TNAItemRepository $tnaItemRepository
     * @param DefaultCommandBus $commandBus
     * @return void
     */
    public function handle(TNAItemRepository $tnaItemRepository, DefaultCommandBus $commandBus)
    {        
        // dd($this->task->toArray());
        $this->tnaItemRepository = $tnaItemRepository;
        $this->commandBus = $commandBus;
        $tnaItem = $this->tnaItemRepository->getById($this->task->tna_item_id);
        if($tnaItem){
            \DB::beginTransaction();
            $tnaItem->item_status_id = $this->task->status_id;
            $tnaItem->is_completed = true;
            $tnaItem->actual_date = $this->task->completion_date;
            // $tnaItem->actual_date = TNAHelper::addDayToDate($task->completion_date, 20);
            if($tnaItem->save()){
                //$tnaItemTransformed = $this->tnaHelper->getTransformedItem($tnaItem);
                //$itemsOrder = (new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tnaItem->tna_id);
                $itemsOrder = $this->updateOneItemOrder($tnaItem);
                $itemsOrder = $this->commandBus->execute(new SyncCommand($itemsOrder, $tnaItem->tna_id));
                //$tna = $tnaItem->tna;
                //$tna->items_order = json_encode($itemsOrder);
                //$tna = (new TNAProjectedDateCalculator)->calculate($tna);
                // if($this->isLastItem($tnaItem->id, json_decode($tna->items_order))){
                //  if(!$this->tnaRepository->completeTNA($tna->id)){
                //      throw new SeException('Unable to save tna state', 500, 50000);
                //  }
                // }
                \DB::commit();
                return $tnaItem;
            }
            else{
                throw new SeException('Error while changing tnaItemStatus', 500, 50000);
            }
        }
        else{
            throw new SeException('TNAItem is not found', 422, 4200420);
        }
    }

    private function updateOneItemOrder($tnaItem)
    {
        $itemsOrder = json_decode($tnaItem->tna->items_order, true);

        if(is_null($tnaItem->dependor_id)) {
            $foundKey = array_search($tnaItem->id, array_column($itemsOrder, 'itemId'));
            $nodes = $itemsOrder[$foundKey]['nodes'];
            $itemsOrder[$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
            $itemsOrder[$foundKey]['nodes'] = $nodes;
        } else {
            $parentKey = array_search($tnaItem->dependor_id, array_column($itemsOrder, 'itemId'));
            if($parentKey !== null) {
                $foundKey = array_search($tnaItem->id, array_column($itemsOrder[$parentKey]['nodes'], 'itemId'));
                $nodes = $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'];
                $itemsOrder[$parentKey]['nodes'][$foundKey] = (array)TNAHelper::getTransformedItem($tnaItem);
                $itemsOrder[$parentKey]['nodes'][$foundKey]['nodes'] = $nodes;
            }
        }
        return $itemsOrder;
    }

}