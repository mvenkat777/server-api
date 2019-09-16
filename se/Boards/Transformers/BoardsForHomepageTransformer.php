<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Picks\Transformers\PickTransformer;
use Platform\Picks\Transformers\MetaPickTransformer;

class BoardsForHomePageTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($board)
	{
        $author = (new MetaUserTransformer)->transform($board->author);
        $salesLead = (new MetaUserTransformer)->transform($board->salesLead);
        $picks = new Collection($board->picks()->take(3)->get(), new MetaPickTransformer);
        $picks = $this->manager->createData($picks)->toArray()['data'];

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
            'picks'=> $picks,
            'collab'=> $collab,
            'updatedAt' => $board->updated_at->toDateTimeString(),
        ];
	}

}
