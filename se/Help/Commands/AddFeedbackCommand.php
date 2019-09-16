<?php

namespace Platform\Help\Commands;

class AddFeedbackCommand
{
	
	/**
     * @var json
     */
	public $feedback;
  
    /**
     * @var string
     */
    
	public $slug;

    function __construct($data, $slug)
    {	
        $this->feedback = isset($data['feedback']) ? $data['feedback'] : null;
        $this->slug = $slug;
        
    } 
}