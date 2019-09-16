<?php
namespace Platform\App\RuleCommanding\ExternalNotification;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Platform\App\Jobs\Job;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\App\RuleCommanding\ExternalNotification\Notifier;
use Platform\App\RuleCommanding\DefaultRuleBus;



class DefaultRuleBusJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    
    protected $task;

    protected $user;

    protected $method;
    
    /**
     * Create a new job instance.
     *
     * TNAItem Model $tnaItem 
     *         
     * @return void
     */
    public function __construct($task, $user, $method)
    {
        $this->task = serialize($task);
       
        $this->user = $user;

        $this->method = $method;
        
        $this->onQueue('emailJob');
        
        
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
    public function handle(DefaultRuleBus $defaultRuleBus)
    {
        $this->task = unserialize($this->task);
        $defaultRuleBus->execute($this->task, $this->user, $this->method);
    }

    
}