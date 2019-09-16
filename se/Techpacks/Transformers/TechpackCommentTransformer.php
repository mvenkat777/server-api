<?php

namespace Platform\Techpacks\Transformers;

use App\TechpackComment;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class TechpackCommentTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(TechpackComment $comment)
    {
        $fractal = new Manager();
        $user = TechpackComment::find($comment->id)->user()->first();
        $user = (new TechpackUserTransformer())->transform($user);

        $replies = TechpackComment::where('parent_id', $comment->id)->get();
        $replies = new Collection($replies, new static());
        $replies = $fractal->createData($replies)->toArray();

        return [
            'id' => $comment->id,
            'user' => $user,
            'parentId' => $comment->parent_id,
            'techpackId' => (string) $comment->techpack_id,
            'file' => (string) $comment->file,
            'comment' => (string) $comment->comment,
            'replies' => $replies['data'],
            'createdAt' => date(DATE_ISO8601, strtotime($comment->created_at)),
            'updatedAt' => date(DATE_ISO8601, strtotime($comment->updated_at)),
        ];
    }
}
