<?php

namespace Platform\App\Repositories\Criteria;

use Platform\App\Repositories\Contracts\CriteriaInterface;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class OrderByCreatedAt extends Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public $order;

    /**
     * @param $libraryItemId
     */
    public function __construct($order = 'desc')
    {
        $this->order = $order;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $query = $model->orderBy('created_at', $this->order);

        return $query;
    }
}
