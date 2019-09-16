<?php

namespace Platform\Techpacks\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Techpacks\Transformers\TechpackUserTransformer;

class CutTicketCommentTransformer extends TransformerAbstract 
{

	public function __construct()
	{
            $this->manager = new Manager();
	}

	public function transform($comment)
	{
            $user = \App\User::find($comment->commented_by);
            $user = (new TechpackUserTransformer())->transform($user);

            return [
                'id' => $comment->id,
                'commentedBy' => $user,
                'techpackId' => (string) $comment->techpack_id,
                'comment' => (string) $comment->comment,
                'createdAt' => date(DATE_ISO8601, strtotime($comment->created_at)),
                'updatedAt' => date(DATE_ISO8601, strtotime($comment->updated_at)),
            ];
	}

}
