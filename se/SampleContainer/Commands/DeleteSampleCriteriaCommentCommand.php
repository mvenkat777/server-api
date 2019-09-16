<?php

namespace Platform\SampleContainer\Commands;

class DeleteSampleCriteriaCommentCommand
{
    /**
     * The sample criteria id
     * @var string
     */
    public $criteriaId;

    /**
     * The sample criteria comment id
     * @var string
     */
    public $commentId;

    /**
     * Constructing to delete
     * @param string $criteriaId
     * @param string $commentId
     */
    public function __construct($criteriaId, $commentId)
    {
        $this->criteriaId = $criteriaId;
        $this->commentId = $commentId;
    }
}