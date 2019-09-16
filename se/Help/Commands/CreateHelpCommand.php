<?php

namespace Platform\Help\Commands;
use Rhumsaa\Uuid\Uuid;


class CreateHelpCommand
{
	
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var string
	 */
	public $app;

	/**
	 * @var string
	 */
	public $slug;
	
    function __construct($data)
    {	
    	$this->slug = substr(Uuid::uuid4()->toString(), 0, 8);
        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->description  = !isset($data['description'])? NULL : $data['description'];
        $this->app = !isset($data['appName'])? NULL : $data['appName'];
    } 
}