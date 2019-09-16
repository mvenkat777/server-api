<?php

namespace Platform\App\Repositories\Criteria;

use Platform\App\Repositories\Contracts\CriteriaInterface;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class BelongsToLibraryItemId extends Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public $libraryItemId;

    /**
     * @param $libraryItemId
     */
    public function __construct($libraryItemId)
    {
        $this->libraryItemId = $libraryItemId;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $query = $model->where('library_item_id', '=', $this->libraryItemId);

        return $query;
    }
}
