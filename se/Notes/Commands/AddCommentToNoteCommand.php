<?php

namespace Platform\Notes\Commands;



class AddCommentToNoteCommand{

	/**
	 * @var string
	 */
    public $comment;

    /**
	 * @var string
	 */
	public $noteId;
	
	/**
	 * @var string
	 */
	public $commentedBy;
	
	function __construct($data, $noteId)
    {	
    	$this->comment = $data['comment'];
        $this->noteId = $noteId;
        $this->commentedBy = \Auth::user()->id;
    }
}