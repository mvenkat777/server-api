<?php

namespace Platform\Notes\Commands;

class ShareNoteCommand {

	/**
	 * @var string
	 */
    public $noteId;

    /**
	 * @var string
	 */
    public $createdBy;

    /**
     * @var string
     */
    public $sharedTo;

    function __construct($data, $id)
    {
        $this->noteId = $id;
        $this->sharedTo = $data['email'];
        $this->sharedBy = \Auth::user()->id;
    }
}