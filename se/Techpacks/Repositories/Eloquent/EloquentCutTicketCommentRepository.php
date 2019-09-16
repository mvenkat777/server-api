<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Repositories\Contracts\CutTicketCommentRepository;
use App\CutTicketComment;

class EloquentCutTicketCommentRepository extends Repository implements CutTicketCommentRepository 
{
    public function model(){
        return 'App\CutTicketComment';
    }

    /**
     * Adds a new cutticket comment
     *
     * @param AddCutTicketCommentCommand $command
     * @return mixed
     */
    public function addComment($command) {
        $comment = [
            'id' => $this->generateUUID(),
            'techpack_id' => $command->techpackId,
            'comment' => $command->comment,
            'commented_by' => $command->commentedBy,
        ];

        return $this->model->create($comment);
    }    

    /**
     * Deletes a comment
     *
     * @param string $commentId
     * @return boolean
     */
    public function deleteComment($commentId) {
        return $this->model->find($commentId)->delete();
    }    

    /**
     * Get all the comments
     *
     * @param string $techpackId
     * @return mixed
     */
    public function getAllComments($techpackId) {
        return $this->model->where('techpack_id', $techpackId)
                           ->paginate(30);
    }    
}
