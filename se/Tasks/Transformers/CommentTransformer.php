<?php
namespace Platform\Tasks\Transformers;

use App\TaskComment;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class CommentTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(TaskComment $comment)
    {
    	$creator = $this->item($comment->creator, new MetaUserTransformer);
        $creator = $this->manager->createData($creator)->toArray();

        return [
			'id'           => $comment->id,
			'taskId'       => $comment->task_id,
			'owner'        => $comment->creator_id,
			'ownerDetails' => $creator['data'],
			'type'         => $comment->type,
			'data'         => $comment->data,
			'created_at'   => $comment->created_at->toDateTimeString(),
			'updated_at'   => $comment->updated_at->toDateTimeString(),
			'deleted_at'   => $comment->deleted_at,
        ];
    }
}
