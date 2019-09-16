<?php

namespace Platform\Users\Commands;

class DeleteTagCommand
{
	/**
	 * @var string
	 */
    public $tagId;

    /**
     * @var string
     */
    public $userId;

     /**
     * @param array $data
     */
    function __construct($userId, $tagId)
    {
    	$this->tagId = $tagId;
        $this->userId = $userId;
    }
}