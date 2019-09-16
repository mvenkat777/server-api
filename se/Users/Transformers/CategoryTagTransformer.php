<?php

namespace Platform\Users\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;

class CategoryTagTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => (string)$category->id,
            'displayName' => (string)$category->displayName,
            'email' => (string)$category->email,
            'isAdmin' => (boolean)$category->isAdmin,
            'isActive' => (boolean)$category->isActive,
            'isBanned' => (boolean)$category->isBanned,
        ];
    }
}
