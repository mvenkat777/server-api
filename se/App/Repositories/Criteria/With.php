<?php

namespace Platform\App\Repositories\Criteria;

use App\Repositories\Contracts\LibraryItemRepository;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class With extends Criteria
{


    public $relation;


    /**
     * @param $attribute
     * @param $value
     * @param string $operator
     * @param LibraryItemRepository $itemRepository
     */
    function __construct( $relations)
    {
        $this->relation = $relations;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply( $model, Repository $repository )
    {
        $query = $model->with( $this->relation);

        return $query;
    }
}