<?php

namespace Platform\SampleContainer\Repositories\Contracts;

interface SampleCriteriaCommentRepository
{
	public function model();

    /**
     * Persists the comment
     * @param AddSampleCriteriaCommentCommand $command
     */
    public function addComment($command);

    /**
     * Get an comment by criteriaId and commentId
     * @param AddSampleCriteriaCommentCommand $command
     */
    public function getByCriteriaIdAndCommentId($criteriaId, $commentId);

    /**
     * Get an comment by id with its relations
     * @param  string $commentId
     * @return mixed
     */
    public function getByIdWithRelations($commentId);
}