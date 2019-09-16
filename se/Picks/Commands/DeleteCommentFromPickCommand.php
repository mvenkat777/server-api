<?php

namespace Platform\Picks\Commands;

class DeleteCommentFromPickCommand 
{
    /**
     * @var string
     */
    public $pickId;

    /**
     * @var array
     */
    public $commentId;

    public function __construct($pickId, $commentId)
    {
        $this->pickId = $pickId;
        $this->commentId = $commentId;
	}
}
