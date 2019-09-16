<?php

namespace Platform\Users\Commands;

class UpdateNoteCommand
{

	/**
	 * @var string
	 */
    public $note;

    /**
	 * @var string
	 */
    public $noteId;

    /**
     * @var string
     */
    public $token;

     /**
     * @var string
     */
    public $userId;


    /**
     * @var string
     */
    public $status;
    

    /**
     * @param array $data $id $token
     */
    function __construct($data , $id, $token)
    {
    	$this->noteId = $id;
        $this->note = $data['note'];
        $this->userId = $data['userId'];
        $this->token = $token;
        $this->status = is_null($data['status'])? 'private':$data['status'];
        
    }
}