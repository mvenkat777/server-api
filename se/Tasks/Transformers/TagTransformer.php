<?php
namespace Platform\Tasks\Transformers;

use League\Fractal\TransformerAbstract;
use App\TaskTag;

class TagTransformer extends TransformerAbstract
{
    public function transform(TaskTag $tag)
    {
        return [
			'id'         => $tag->id,
			'title'      => $tag->title,
			// 'createdAt' => $tag->created_at,
			// 'updatedAt' => $tag->updated_at,
			// 'deletedAt' => $tag->deleted_at,
        ];
    }
}
