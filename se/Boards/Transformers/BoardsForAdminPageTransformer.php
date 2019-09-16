<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\CollabTransformer;
use Platform\Picks\Transformers\MetaPickTransformer;
use Platform\Users\Transformers\MetaUserTransformer;

class BoardsForAdminPageTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($board)
	{
        $author = (new MetaUserTransformer)->transform($board->author);
        $salesLead = (new MetaUserTransformer)->transform($board->salesLead);
        $collabs = new Collection($board->collabs()->get(), new CollabTransformer);
        $collabs = $this->manager->createData($collabs)->toArray()['data'];
        if (count($collabs) == 1) {
        	$collabs = $collabs[0];
        }

        return [
            'id' => $board->id,
            'name' => $board->name,
            'salesLead' => $salesLead,
            'author' => $author,
            'collabs' => $collabs,
        ];
	}

}
