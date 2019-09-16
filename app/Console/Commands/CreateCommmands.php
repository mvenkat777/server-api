<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateCommmands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:command {name} {feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command command and its handler.';

    protected $feature;

    protected $name;

    protected $basePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->basePath = base_path().'/se/';
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
        $this->name = preg_replace('/Command/i', '', $this->name);
        // $this->name = preg_match('/Command/i', $this->name) ? $this->name : $this->name.'Command';

        //Do All the checking
        // if(!is_dir($basePath.$feature)){
        //     echo "$feature not found";
        //     exit();
        // }
        // echo $this->basePath.$this->feature.'/Commands/'.$this->name.'Command';
        $myfile = fopen("$this->basePath/$this->feature/Commands/$this->name"."Command.php", "w") or die("Unable to open file!");
        $txt = "<?php\nnamespace Platform\\$this->feature\Commands;\n\n";
        fwrite($myfile, $txt);
        $txt = "class $this->name"."Command \n{\n\n";
        fwrite($myfile, $txt);
        $txt = "\tpublic function __construct(){\n\n\t}\n\n}";
        fwrite($myfile, $txt);
        fclose($myfile);

        //For Command Handler
        // echo "Handler";
        $myfile = fopen("$this->basePath/$this->feature/Handlers/Commands/$this->name"."CommandHandler.php", "w") or die("Unable to open file!");
        $txt = "<?php\nnamespace Platform\\$this->feature\Handlers\Commands;\n\nuse Platform\App\Commanding\CommandHandler;\n\n";
        fwrite($myfile, $txt);
        $txt = "class $this->name"."CommandHandler implements CommandHandler \n{\n\n";
        fwrite($myfile, $txt);
        $txt = "\tpublic function __construct(){\n\n\t}\n\n}";
        fwrite($myfile, $txt);
        fclose($myfile);

        echo "Command Created\n";
    }
}
