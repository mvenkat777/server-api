<?php

namespace Platform\TNA\Jobs;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Platform\App\Jobs\Job;



class SaveTaskJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $tnaItem;


    
    /**
     * Create a new job instance.
     *
     * TNAItem Model $tnaItem 
     *         
     * @return void
     */
    public function __construct($tnaItem)
    {
        $this->tnaItem=$tnaItem;

        $this->onQueue('mediumJob');
        
        
    }

    /**
     * Execute the job.
     *
     * Save Task according to TNA
     * 
     * @param  TNAItem Model $tnaItem 
     * @return Task Model          
     *
     * 
     * @return void
     */
    public function handle()
    {        
        if(is_null($this->tnaItem->task)){
            return;
        }
        $task = $this->tnaItem->task;
        $task->title = $this->tnaItem->title;
        $task->description = $this->tnaItem->description;
        $task->due_date = $this->tnaItem->planned_date;
        $task->assignee_id = $this->tnaItem->representor_id;
        // update the relationship otherwise it will cause problem in activity by taking old data
        $task = $task->setRelation('assignee', $task->assignee()->first());
        if($task->save()){
            return $task;
        }

    }
}