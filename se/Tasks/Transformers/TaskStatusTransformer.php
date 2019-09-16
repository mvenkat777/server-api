<?php
namespace Platform\Tasks\Transformers;

use League\Fractal\TransformerAbstract;
use App\TaskStatus;

class TaskStatusTransformer extends TransformerAbstract
{
    public function transform(TaskStatus $taskStatus)
    {
        return [
			'id'         => $taskStatus->id,
			'status'      => $taskStatus->status,
			'created_at' => $taskStatus->created_at->toDateTimeString(),
			'updated_at' => $taskStatus->updated_at->toDateTimeString()
        ];
    }
}
