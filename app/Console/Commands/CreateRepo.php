<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:repo {name} {namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Repository for given input and register in repository provider';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $basePath = base_path().'/se/';
        $repoName = $this->argument('name');
        $repoNamespace = $this->argument('namespace');

        if(!is_dir($basePath.$repoNamespace)){
            echo "$repoNamespace not found";
            exit();
        }
        if (!is_dir($basePath.$repoNamespace.'/Repositories')) {
            echo "creating Repositories folder";
            exit();
        }
        echo $basePath.$repoNamespace;
    }

    private function createFolder($path, $name){
        
    }
}
