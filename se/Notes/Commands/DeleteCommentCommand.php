<?php

namespace Platform\Notes\Commands;

class DeleteCommentCommand {

	/**
	 * @var string
	 */
    public $noteId;

    /**
     * @var string
     */
    public $commentId;

    function __construct($noteId, $commentId)
    {
        $this->noteId = $noteId;
        $this->commentId = $commentId;
    }
}