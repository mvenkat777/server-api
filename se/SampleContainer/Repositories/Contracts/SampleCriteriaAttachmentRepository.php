<?php

namespace Platform\SampleContainer\Repositories\Contracts;

interface SampleCriteriaAttachmentRepository
{
	public function model();

    /**
     * Persists the attachment
     * @param AddSampleCriteriaAttachmentCommand $command
     */
    public function addAttachment($command);

    /**
     * Get an attachment by criteriaId and attachmentId
     * @param AddSampleCriteriaAttachmentCommand $command
     */
    public function getByCriteriaIdAndAttachmentId($criteriaId, $attachmentId);

    /**
     * Get an attachment by id with its relations
     * @param  string $attachmentId
     * @return mixed
     */
    public function getByIdWithRelations($attachmentId);
}