<?php
namespace Platform\Tasks\Transformers;

use League\Fractal\TransformerAbstract;
use App\TaskStatus;

class TaskStatusNoteTransformer extends TransformerAbstract
{
    public function transform($taskStatusNote)
    {
    	$taskStatusNotePivot = $taskStatusNote->toArray()['pivot'];
        return [
			'status'        => $taskStatusNote->status,
			'note'      => $taskStatusNotePivot['note'],
			'createdAt' => $taskStatusNotePivot['created_at'],
			// 'updatedAt' => $taskStatusNote['updated_at']
        ];
    }
}
