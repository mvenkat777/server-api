<?php

namespace Platform\Help\Commands;

class AddDislikeCommand
{
	
	/**
     * @var string
     */
	public $dislike;
  
     /**
     * @var string
     */
    
	public $slug;

    function __construct($data, $slug)
    {	
        $this->dislike = isset($data['dislike']) ? $data['dislike'] : null;
        $this->slug = $slug;
        
    } 
}