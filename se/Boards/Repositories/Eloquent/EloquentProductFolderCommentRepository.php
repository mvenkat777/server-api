<?php

namespace Platform\Boards\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Boards\Repositories\Contracts\ProductFolderCommentRepository;
use App\ProductFolderComment;

class EloquentProductFolderCommentRepository extends Repository implements ProductFolderCommentRepository
{
	public function model(){
		return 'Platform\Boards\Models\ProductFolderComment';
	}

	/**
	 * Adding a comment to product folder
	 *
	 * @param string $productFolderId
	 * @param array $data
	 */
    public function addComment($productFolderId, $data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'product_folder_id' => $productFolderId,
            'comment' => $data['comment'],
            'commentator_id' => \Auth::user()->id,
        ];

        return $this->create($data);
    }

}
