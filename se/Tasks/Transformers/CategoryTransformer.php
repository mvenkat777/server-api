<?php
namespace Platform\Tasks\Transformers;

use League\Fractal\TransformerAbstract;
use App\TaskCategory;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(TaskCategory $category)
    {
        return [
			'id'         => $category->id,
			'title'      => $category->title,
			// 'created_at' => $category->created_at->toDateTimeString(),
			// 'updated_at' => $category->updated_at->toDateTimeString(),
			// 'deleted_at' => $category->deleted_at,
        ];
    }
}
