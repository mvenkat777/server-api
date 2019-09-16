<?php

namespace Platform\Notes\Commands;



class UpdateCommentCommand{

	/**
	 * @var string
	 */
    public $comment;

    /**
	 * @var string
	 */
    public $commentId;

    /**
	 * @var string
	 */
	public $noteId;
	
	/**
	 * @var string
	 */
	public $commentedBy;
	
	function __construct($data, $noteId, $commentId)
    {	
    	$this->commentId = $commentId;
    	$this->comment = $data['comment'];
        $this->noteId = $noteId;
        $this->commentedBy = \Auth::user()->id;
    }
}