<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Picks\Transformers\MetaPickTransformer;
use League\Fractal\Resource\Collection;

class ProductFolderForSidebarTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($productFolder)
	{
        $picks = new Collection($productFolder->picks()->take(5)->get(), new MetaPickTransformer);
        $picks = $this->manager->createData($picks)->toArray()['data'];

        return [
            'id' => $productFolder->id,
            'name' => $productFolder->name,
            'counts' => [
                'comments' => count($productFolder->comments),
            ],
            'picks' => $picks,
            'updatedAt' => $productFolder->updated_at->toDateTimeString(),
        ];
	}

}
