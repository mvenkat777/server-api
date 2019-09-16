<?php

namespace Platform\Users\Commands;

class DeleteNoteCommand
{
	/**
     * @var string
     */
    public $noteId;

    /**
     * @var string
     */
    public $token;
    

    /**
     * @param array $data
     */
    function __construct($id, $token)
    {
    	$this->noteId = $id;
        $this->token = $token;
    }
}