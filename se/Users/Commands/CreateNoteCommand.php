<?php

namespace Platform\Users\Commands;

class CreateNoteCommand
{
	/**
	 * @var string
	 */
    public $note;

    /**
     * @var string
     */
    // public $writtenBy;
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
     * @param array $data
     */
    function __construct($data, $token)
    {
        $this->note = $data['note'];
        $this->userId = $data['userId'];
        $this->token = $token;
        $this->status = is_null($data['status'])? 'private':$data['status'];
        
    }
}