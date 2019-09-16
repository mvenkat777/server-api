<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;

class CreateValidator extends SeConsole
{
   
    protected $name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature = 'create:validator {name} {feature} {--same}';
        $this->description = 'create validator.';
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
        
        if(!$this->option('same')){
            $this->name = preg_replace('/Validator/i', '', $this->name);
            $this->name = $this->name.'Validator';
        }
        // $this->name = preg_match('/Command/i', $this->name) ? $this->name : $this->name.'Command';

        //Do All the checking
        if(!is_dir($this->basePath.$this->feature)){
            echo "$this->feature not found";
            exit();
        }
        if(!is_dir($this->basePath.$this->feature.'/Validators')){
            echo "$this->feature/Validators not found \n";
            echo "creating Validators... \n";
            mkdir($this->basePath.$this->feature.'/Validators');
        }
        
        $myfile = fopen("$this->basePath/$this->feature/Validators/$this->name".".php", "w") or die("Unable to open file!");
        $txt = "<?php\n\nnamespace Platform\\$this->feature\Validators;\n\n";
        fwrite($myfile, $txt);
        $txt = "use Platform\App\Validation\DataValidator;\n\n";
        fwrite($myfile, $txt);
        $txt = "class $this->name"." extends DataValidator \n{\n";
        fwrite($myfile, $txt);
        $txt = "\t/**\n\t*@var array\n\t*/\n\tprotected ".'$rules = [];'."\n\n";
        fwrite($myfile, $txt);
        $txt = "\tpublic function __construct(){\n\n\t}\n\n}";
        fwrite($myfile, $txt);
        fclose($myfile);

        echo "Validator Created\n";
    }
}
