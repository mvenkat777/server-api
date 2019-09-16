<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;

class CreateRepo extends SeConsole
{
   
    protected $name;

    protected $model;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature = 'create:repo {name} {feature} {--model=}';
        $this->description = 'Create Contract and eloquent repository and register it';
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
        $this->name = $this->argument('name');
        $this->name = preg_replace('/Repository/i', '', $this->name);
        
        $this->model = $this->option('model');
        if($this->model == ''){ $this->model = $this->name; }
        
        //Do All the checking
        // if(!is_dir($basePath.$feature)){
        //     echo "$feature not found";
        //     exit();
        // }

        $this->createContract($this->name, $this->feature, $this->model);
        $this->createEloquent($this->name, $this->feature, $this->model);
        
        echo "Repository Created\n";
    }

    private function createContract($name, $feature){
        $myfile = fopen("$this->basePath/$feature/Repositories/Contracts/$name"."Repository.php", "w") or die("Unable to open file!");

        $txt = "<?php\n\nnamespace Platform\\$feature\Repositories\Contracts;\n\n";
        fwrite($myfile, $txt);

        $txt = "interface $name"."Repository \n{\n";
        fwrite($myfile, $txt);

        $txt = "\tpublic function model();\n}";
        fwrite($myfile, $txt);
        
        fclose($myfile);
    }

    private function createEloquent($name, $feature, $model){
        $myfile = fopen("$this->basePath/$this->feature/Repositories/Eloquent/Eloquent$this->name"."Repository.php", "w") or die("Unable to open file!");

        $txt = "<?php\n\nnamespace Platform\\$feature\Repositories\Eloquent;\n\nuse Platform\App\Repositories\Eloquent\Repository;\n";
        fwrite($myfile, $txt);

        $txt = "use Platform\\$feature\Repositories\Contracts\\$name"."Repository;\n";
        fwrite($myfile, $txt);
        //write for Model
        $txt = "use App\\$model;\n\n";
        fwrite($myfile, $txt);

        $txt = "class Eloquent$name"."Repository extends Repository implements $name"."Repository \n{\n\n";
        fwrite($myfile, $txt);

        $txt = "\tpublic function model(){\n\t\treturn 'App\\$model';\n\t}\n\n}";
        fwrite($myfile, $txt);

        fclose($myfile);
    }
}
