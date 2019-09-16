<?php

namespace Platform\App\Repositories\Criteria;

use Platform\App\Repositories\Contracts\CriteriaInterface;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class OrderBy extends Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public $orderBy;
    public $order;

    public function __construct($orderBy, $order)
    {
        $this->orderBy = $orderBy;
        $this->order = $order;
    }


    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $query = $model->orderBy($this->orderBy, $this->order);

        return $query;
    }
}
