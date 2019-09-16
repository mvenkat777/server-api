<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class ProductFolderCommentTransformer extends TransformerAbstract
{
	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($comment)
	{
        $commentator = (new MetaUserTransformer)->transform($comment->commentator);

        return [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'commentator' => $commentator,
        ];
	}

}
