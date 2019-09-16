<?php

namespace Platform\Notes\Commands;

class DeleteNoteCommand {

	/**
	 * @var string
	 */
    public $noteId;

    /**
	 * @var string
	 */
    public $createdBy;

    function __construct($id)
    {
        $this->noteId = $id;
        $this->createdBy = \Auth::user()->id;
    }
}