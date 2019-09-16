<?php

namespace Platform\CollabBoard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use Platform\Boards\Transformers\BoardTransformer;

class ProductFolderTransformer extends TransformerAbstract 
{
    public function __construct() {
        $this->manager = new Manager();
    }

	public function transform($productFolder)
    {
        $board = \Platform\CollabBoard\Models\Board::find($productFolder->board_id);
        $board = new Item($board, new BoardTransformer());
        $board = $this->manager->createData($board)->toArray()['data'];
        return [
            'id' => $productFolder->id, 
            'board' => $board, 
            'name' => $productFolder->name, 
            'createdAt' => $productFolder->created_at->toDateTimeString(), 
            'updatedAt' => $productFolder->updated_at->toDateTimeString(), 
        ];
	}

}
