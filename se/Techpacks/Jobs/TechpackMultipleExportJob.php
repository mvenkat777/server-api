<?php

namespace Platform\Techpacks\Jobs;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Jobs\Job;
use Platform\Techpacks\Commands\MultipleTechpackExportCommand;

class TechpackMultipleExportJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;
    protected $email;

    
    /**
     * Create a new job instance.
     *
     * @param  Request  $request
     *         Email $email
     * @return void
     */
    public function __construct($request, $email)
    {
        $this->request=$request;
        $this->email = \Auth::user()->email;
        $this->onQueue('mediumJob');

    }

    /**
     * Execute the job.
     *
     * @param  DefaultCommandBus  $commandBus
     * @return void
     */
    public function handle(DefaultCommandBus $commandBus)
    {
        $request = $this->request;
        $request['email'] = $this->email; 
        // dd($request);       
        $commandBus->execute(
                new MultipleTechpackExportCommand(
                    $request,
                    $request['techpackIds']
                )
            );

        
    }
}