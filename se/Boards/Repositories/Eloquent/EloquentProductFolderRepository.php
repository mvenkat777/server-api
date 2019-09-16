<?php

namespace Platform\Boards\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Boards\Repositories\Contracts\ProductFolderRepository;
use App\ProductFolder;

class EloquentProductFolderRepository extends Repository implements ProductFolderRepository 
{

	public function model(){
		return 'Platform\Boards\Models\ProductFolder';
	}

    /**
     * Creates a product folder
     *
     * @param mixed $data
     */
    public function createProductFolder($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'name' => $data['name'],
            'cover' => isset($data['cover']) ? json_encode($data['cover']) : null,
        ];

        return $this->model->create($data);
    }

    /**
     * Update a product folder
     *
     * @param array $data
     * @param array $productFolderid
     */
    public function updateProductFolder($data, $productFolderid)
    {
        $data = [
            'name' => $data['name'],
            'cover' => isset($data['cover']) ? json_encode($data['cover']) : null,
        ];

        return $this->model->update($data, $productfolderId);
    }
}
