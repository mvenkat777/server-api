<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;

class CreateTransformer extends SeConsole
{
   
    protected $name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature = 'create:transformer {name} {feature} {--same} {--model=}';
        $this->description = 'create transformer.';
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
            $this->name = preg_replace('/Transformer/i', '', $this->name);
            $this->name = $this->name.'Transformer';
        }
        // $this->name = preg_match('/Command/i', $this->name) ? $this->name : $this->name.'Command';

        //Do All the checking
        if(!is_dir($this->basePath.$this->feature)){
            echo "$this->feature not found";
            exit();
        }
        if(!is_dir($this->basePath.$this->feature.'/Validators')){
            echo "$this->feature/Transformers not found \n";
            echo "creating Transformers... \n";
            mkdir($this->basePath.$this->feature.'/Transformers');
        }
        
        $myfile = fopen("$this->basePath/$this->feature/Transformers/$this->name".".php", "w") or die("Unable to open file!");
        $txt = "<?php\n\nnamespace Platform\\$this->feature\Transformers;\n\n";
        fwrite($myfile, $txt);
        $txt = "use League\Fractal\Manager;\n";
        fwrite($myfile, $txt);
        $txt = "use League\Fractal\TransformerAbstract;\n\n";
        fwrite($myfile, $txt);
        $txt = "class $this->name"." extends TransformerAbstract \n{\n\n";
        fwrite($myfile, $txt);
        $txt = "\tpublic function __construct()\n\t{\n\t\t";
        fwrite($myfile, $txt);
        $txt = '$this->manager = new Manager();'."\n\t}\n";
        fwrite($myfile, $txt);

        $tname = preg_replace('/Transformer/i', '', $this->name);
        $txt = "\n\tpublic function transform($$tname)\n\t{\n\t\t";
        fwrite($myfile, $txt);
        $txt = 'return [];'."\n\t}\n\n}";
        fwrite($myfile, $txt);
        fclose($myfile);

        echo "Transformer Created\n";
    }
}
