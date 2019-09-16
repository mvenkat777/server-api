<?php

namespace Platform\Form\Jobs;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Platform\App\Jobs\Job;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Form\Commands\SendFormExternalNotificationCommand;


class SendFormExternalNotificationJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    protected $state;
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
    public function __construct($data, $state)
    {
        $this->data=$data;

        $this->state=$state;

        $this->onQueue('mediumJob');
        
        
    }

    /**
     * Execute the job.
     *
     *
     * @param DefaultCommandBus $commandBus
     * @return void
     */
    public function handle(DefaultCommandBus $commandBus)
    {
        return true;
        // $commandBus->execute(new SendFormExternalNotificationCommand($this->data, $this->state));
    }

    

}