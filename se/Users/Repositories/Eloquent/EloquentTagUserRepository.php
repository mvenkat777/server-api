<?php

namespace Platform\Users\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Repositories\Contracts\TagUserRepository;

class EloquentTagUserRepository extends Repository implements TagUserRepository
{
    public function model()
    {
        return 'App\UserUserTag';
    }

    public function addTag($userId, $tagId, $taggedBy)
    {
        $tag = [
            'tagged_by' => $taggedBy,
            'user_id' => $userId,
            'tag_id' => $tagId,
        ];

        return $this->model->create($tag);
    }

    public function getTagUser($userId, $tagId)
    {
        return $this->model->where('user_id', '=', $userId)->where('tag_id', '=', $tagId)->first();
    }

    public function getAlTagOfUser($command)
    {
        return $this->model->where('user_id', '=', $command->userId)
                            ->join('user_tag', 'user_user_tag.tag_id', '=', 'user_tag.id')
                            ->select('user_tag.*', 'user_user_tag.*')
                            ->get();
    }

    public function delete($command)
    {
        $user = \App\User::find($command->userId);
        $tag = $this->model->where('user_id', '=', $command->userId)
                            ->where('tag_id', '=', $command->tagId)
                            ->delete();
        $user->recordCustomActivity($user, ['tags', [$command->tagId], false], 'deleted');
        return $tag;
    }
}
