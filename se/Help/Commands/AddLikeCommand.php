<?php

namespace Platform\Help\Commands;



class AddLikeCommand
{
	/**
     * @var number
     */
	public $like;
     /**
     * @var string
     */
	public $slug;
    public $dislike;

    function __construct($data, $slug)
    {	
        $this->like = isset($data['like']) ? $data['like'] : null;
        $this->dislike = isset($data['disliek']) ? $data['dislike'] : null;
        $this->slug = $slug;
        
    } 
}