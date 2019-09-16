<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\CollabBoard\Repositories\Contracts\ProductFolderRepository;

use Rhumsaa\Uuid\Uuid;
use Platform\CollabBoard\Transformers\ProductFolderTransformer;
use League\Fractal\Manager;

class ProductFolderController extends ApiController
{
    private $productFolder;
    public function __construct(ProductFolderRepository $productFolder) 
    {
        $this->productFolder = $productFolder;

		parent::__construct(new Manager());
    }

    public function store($boardId, Request $request)
    {
        $data = [
            'id' => Uuid::uuid4()->toString(), 
            'board_id' => $boardId, 
            'name' => $request->name,
        ];

        $productFolder = $this->productFolder->create($data);
    	if ($productFolder) {
    		return $this->respondWithNewItem($productFolder, new ProductFolderTransformer, 'board');
    	}

    	return $this->respondInternalError();
    }

    /**
     * Get all boards
     *
     * @param  string $boardId
     * @return mixed
     */
    public function index($boardId)
    {
    	$productFolders = $this->productFolder->getByBoardId($boardId);
    	if ($productFolders) {
    		return $this->respondWithPaginatedCollection($productFolders, new ProductFolderTransformer, 'board');
    	}

    	return $this->respondNotFound();
    }

}
