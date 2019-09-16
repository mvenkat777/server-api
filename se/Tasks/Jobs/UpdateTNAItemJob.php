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


class UpdateTNAItemJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $task;
    

    
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
     * @param
     * @return void
     */
    public function handle()
    {
        if(!is_null($this->task->tnaItem)){
            $tnaItem = $this->task->tnaItem;
            // $taskDays = TNAHelper::getTaskDays($task->due_date, $tnaItem);
            $tnaItem->title = $this->task->title;
            $tnaItem->description = $this->task->description;
            $tnaItem->representor_id = $this->task->assignee_id;
            // $tnaItem->planned_date = $task->due_date;
            $tnaItem->item_status_id = $this->task->status_id;
            if($tnaItem->save()){
                $itemsOrder = $this->updateOneItemOrder($tnaItem);
                $tnaItem->tna->items_order = json_encode($itemsOrder);
                $tnaItem->tna->save();
            }
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