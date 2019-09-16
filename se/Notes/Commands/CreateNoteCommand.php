<?php

namespace Platform\Notes\Commands;



class CreateNoteCommand{

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
	public $createdBy;
	
	function __construct($data)
    {	
    	$this->title = $data['title'];
        $this->description = $data['description'];
        $this->createdBy = \Auth::user()->id;
    }
}