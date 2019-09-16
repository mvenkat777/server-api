<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class BoardTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($board)
	{
        $author = (new MetaUserTransformer)->transform($board->author);
        $salesLead = (new MetaUserTransformer)->transform($board->salesLead);

        $collab = $board->collabs()->first();
        if ($collab) {
            $collab = [
                'id' => $collab->id,
                'logo' => json_decode($collab->logo),
            ];
        } else {
            $collab = null;
        }

        return [
            'id' => $board->id,
            'name' => $board->name,
            'cover' => json_decode($board->cover),
            'salesLead' => $salesLead,
            'author' => $author,
            'collab' => $collab,
            'updatedAt' => $board->updated_at->toDateTimeString(),
        ];
	}

}
