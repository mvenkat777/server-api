<?php
namespace Platform\Tasks\Transformers;

use App\TaskAttachment;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class AttachmentTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

	public function transform(TaskAttachment $attachment)
	{
		// echo $attachment->creator->id.' ';
		$creator = $this->item($attachment->creator, new MetaUserTransformer);
        $creator = $this->manager->createData($creator)->toArray();

		return [
			'id'         => $attachment->id,
			'taskId'     => $attachment->task_id,
			'type'       => $attachment->type,
			'data'       => json_decode($attachment->data),
			'creator'	 => $creator['data'],
			'created_at' => $attachment->created_at->toDateTimeString(),
			'updated_at' => $attachment->updated_at->toDateTimeString(),
			'deleted_at' => $attachment->deleted_at,
		];
	}
}
