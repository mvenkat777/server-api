<?php

namespace Platform\Techpacks\Commands;

class GetTechpackMetaCommand
{
    /**
     * The app which is requesting for the techpack meta
     * @var string
     */
    public $app;

    /**
     * Constructing the command
     */
	public function __construct($app){
        $this->app = $app;
	}
}