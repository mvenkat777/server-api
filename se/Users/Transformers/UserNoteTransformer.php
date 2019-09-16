<?php

namespace Platform\Users\Transformers;

use League\Fractal\TransformerAbstract;
use App\UserNote;

class UserNoteTransformer extends TransformerAbstract
{
    public function transform(UserNote $note)
    {
        return [
            'id' => $note->id,
            'writtenBy' => (string)$note->written_by,
            'userId' => (string)$note->user_id,
            'note' => (string)$note->note,
            'status' => $note->status,
            'createdAt' => $note->created_at->toDateTimeString(),
            'updatedAt' => $note->updated_at->toDateTimeString()
        ];
    }
}
