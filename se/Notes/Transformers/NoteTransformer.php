<?php

namespace Platform\Notes\Transformers;

use App\Note;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Notes\Transformers\NoteCommentTransformer;

class NoteTransformer extends TransformerAbstract
{
    public function transform(Note $note)
    {
        $fractal = new Manager();

        if(isset($note->comments)){
            $comments = new Collection($note->comments, new NoteCommentTransformer);
            $comments = $fractal->createData($comments)->toArray()['data'];
        }

        $response = [
            'noteId' => (string)$note->id,
            'title' => (string)$note->title,
            'description' => $note->description,
            'createdBy' => (string)$note->created_by,
            'createdAt' => $note->created_at->toDateTimeString(),
            'updatedAt' => $note->updated_at->toDateTimeString()
        ];

        if (isset($comments)) {
            $response['comments'] = $comments;
        }

        return $response;
    }

}
