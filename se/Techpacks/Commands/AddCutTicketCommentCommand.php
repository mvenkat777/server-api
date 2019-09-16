<?php

namespace Platform\Techpacks\Commands;

class AddCutTicketCommentCommand 
{

    /**
     * @var string
     */
    public $techpackId;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $commentedBy;

    /**
     * @param array $data
     * @param string $techpackId
     */
    public function __construct($data)
    {
        $this->techpackId = $data['techpackId'];
        $this->comment = $data['comment'];
        $this->commentedBy = $data['commentedBy'];
    }

}
