<?php

namespace Platform\Techpacks\Commands;

class DeleteCutTicketCommentCommand 
{
    /**
     * @var string
     */
    public $commentId;

    /**
     * @param string $commentId
     */
    public function __construct($commentId){
        $this->commentId = $commentId;
    }
}
