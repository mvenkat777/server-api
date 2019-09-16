<?php
namespace Platform\App\Console;

use Illuminate\Console\Command;

class SeConsole extends Command
{
	protected $signature;

	protected $description;

	protected $feature;

	protected $basePath;

	protected $folders = ['commands', 'handlers', 'repositories', 'transformers', 'validators', 'providerds'];

	public function __construct()
    {
    	$this->basePath = base_path().'/se/';
        parent::__construct();
    }

}