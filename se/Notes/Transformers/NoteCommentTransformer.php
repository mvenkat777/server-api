<?php

namespace Platform\Notes\Transformers;

use App\NoteComment;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\UserTransformer;

class NoteCommentTransformer extends TransformerAbstract
{
    public function transform(NoteComment $comment)
    {
        $fractal = new Manager();

        $user = new Item($comment->user, new UserTransformer);
        $user = $fractal->createData($user)->toArray()['data'];

        return [
            'commentId' => (string)$comment->id,
            'comment' => (string)$comment->comment,
            'noteId' => $comment->note_id,
            'commentBy' => (string)$comment->commented_by,
            'commentedByDetails' => $user,
            'createdAt' => $comment->created_at->toDateTimeString(),
            'updatedAt' => $comment->updated_at->toDateTimeString()
        ];
    }

}
