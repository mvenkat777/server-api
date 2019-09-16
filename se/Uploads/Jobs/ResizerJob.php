<?php

namespace Platform\Uploads\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Jobs\Job;
use Platform\Uploads\Helpers\ImageResizerHelpers;

class ResizerJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $fileName;
    protected $file;
    protected $command;

    
    /**
     * Create a new job instance.
     *
     * @param  fileName  $fileName
     *         file $file
     *         command $command
     * @return void
     */
    public function __construct($fileName, $file , $command)
    {
        $this->fileName=$fileName;
        $this->file = $file;
        $this->command = json_decode($command);

    }
    
    /**
     * Execute the job.
     *
     * @param  DefaultCommandBus  $commandBus
     * @return void
     */
    public function handle()
    {
        //dd($this->file);
        (new ImageResizerHelpers())->makeImagesSizesLocalCopyAndUpload($this->fileName,$this->file,$this->command);
        
    }
}