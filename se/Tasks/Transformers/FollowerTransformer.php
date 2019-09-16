<?php
namespace Platform\Tasks\Transformers;

use App\TaskFollower;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class FollowerTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(TaskFollower $follower)
    {
    	$user = $this->item($follower->user, new MetaUserTransformer);
        $user = $this->manager->createData($user)->toArray();

        return [
			'id'              => $follower->id,
			'taskId'          => $follower->task_id,
			'follower'        => $follower->follower_id,
			'followerDetails' => $user['data'],
			'createdAt'      => $follower->created_at->toDateTimeString(),
			'updatedAt'      => $follower->updated_at->toDateTimeString(),
			'deletedAt'      => $follower->deleted_at,
        ];
    }
}
