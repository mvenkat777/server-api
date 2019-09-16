<?php

namespace Platform\SampleContainer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaCommentRepository;
use App\SampleCriteriaComment;

class EloquentSampleCriteriaCommentRepository extends Repository implements SampleCriteriaCommentRepository
{
    /**
     * Get the SampleCriteriaComment model
     * @return string
     */
    public function model(){
        return 'App\SampleCriteriaComment';
    }

    /**
     * Persists the comment
     * @param AddSampleCriteriaCommentCommand $command
     */
    public function addComment($command)
    {
        $data = [
            'id' => $this->generateUUID(),
            'sample_criteria_id' => $command->criteriaId,
            'comment' => $command->comment,
            'commenter_id' => $command->commenterId,
        ];

        $comment = $this->create($data);
        return $this->getByIdWithRelations($comment->id);
    }

    /**
     * Get an comment by criteriaId and commentId
     * @param AddSampleCriteriaCommentCommand $command
     */
    public function getByCriteriaIdAndCommentId($criteriaId, $commentId)
    {
        return $this->model->where('sample_criteria_id', $criteriaId)
                           ->where('id', $commentId)
                           ->first();
    }

    /**
     * Get an comment by id with its relations
     * @param  string $commentId
     * @return mixed
     */
    public function getByIdWithRelations($commentId)
    {
        return $this->model->with(['commenter'])
                           ->where('id', $commentId)
                           ->first();
    }
}