<?php

namespace Platform\Boards\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Picks\Transformers\PickTransformer;
use League\Fractal\Resource\Collection;

class ProductFolderTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($productFolder)
	{
        $picks = new Collection($productFolder->picks, new PickTransformer);
        $picks = $this->manager->createData($picks)->toArray()['data'];

        $board = $productFolder->boards()->first();

        if ($board) {
            $board = [
                'id' => $board->id,
                'name' => $board->name,
            ];
        } else {
            $board = null;
        }

        return [
            'id' => $productFolder->id,
            'name' => $productFolder->name,
            'board' => $board,
            'counts' => [
                'comments' => count($productFolder->comments),
            ],
            'cover' => json_decode($productFolder->cover),
            'picks' => $picks,
            'updatedAt' => $productFolder->updated_at->toDateTimeString(),
        ];
	}

}
