<?php

namespace Platform\Notes\Commands;

class UpdateNoteCommand {

    /**
    * @var string
    */
    public $noteId;

    /**
    * @var string
    */
    public $title;

    /**
    * @var string
    */
    public $description;

    /**
    * @var string
    */
    public $createdBy;

    function __construct($data, $noteId)
    {
        $this->noteId = $noteId;
        $this->title = isset($data['title'])? $data['title']:NULL;
        $this->description = isset($data['description'])? $data['description']:NULL;
        $this->createdBy = \Auth::user()->id;
    }


} 