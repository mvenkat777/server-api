<?php

namespace Platform\SampleContainer\Commands;

class DeleteSampleCriteriaAttachmentCommand
{
    /**
     * The sample criteria id
     * @var string
     */
    public $criteriaId;

    /**
     * The sample criteria attachment id
     * @var string
     */
    public $attachmentId;

    /**
     * Constructing to delete
     * @param string $criteriaId
     * @param string $attachmentId
     */
    public function __construct($criteriaId, $attachmentId)
    {
        $this->criteriaId = $criteriaId;
        $this->attachmentId = $attachmentId;
    }
}