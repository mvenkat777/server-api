<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;

class CreateFeature extends SeConsole
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature = 'create:feature {feature}';
        $this->description = 'Command command and its handler.';
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->feature = $this->argument('feature');
        
        //Do All the checking
        // if(!is_dir($basePath.$feature)){
        //     echo "$feature not found";
        //     exit();
        // }
        // echo $this->basePath.$this->feature.'/Commands/'.$this->name.'Command';

        $featurePath = $this->basePath.'/'.$this->feature;
        mkdir($featurePath);
        mkdir($featurePath.'/Commands');
        mkdir($featurePath.'/Handlers');
        mkdir($featurePath.'/Handlers/Commands');
        mkdir($featurePath.'/Repositories');
        mkdir($featurePath.'/Repositories/Contracts');
        mkdir($featurePath.'/Repositories/Eloquent');
        mkdir($featurePath.'/Validators');
        mkdir($featurePath.'/Transformers');
        mkdir($featurePath.'/Providers');

        echo "Feature Created\n";
    }
}
