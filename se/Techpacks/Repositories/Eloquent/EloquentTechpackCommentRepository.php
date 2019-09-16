<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Commands\AddTechpackCommentCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackCommentRepository;

class EloquentTechpackCommentRepository extends Repository implements TechpackCommentRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'App\TechpackComment';
    }

    public function addComment(AddTechpackCommentCommand $command)
    {
        $comment = [
            'user_id' => $command->userId,
            'comment' => $command->comment,
            'techpack_id' => $command->techpackId,
            'parent_id' => $command->parentId,
            'file' => $command->file,
        ];

        return $this->model->create($comment);
    }

    public function getByTechpackId($techpackId)
    {
        return $this->model->where('techpack_id', $techpackId)
                           ->where('parent_id', null)
                           ->get();
    }
}
