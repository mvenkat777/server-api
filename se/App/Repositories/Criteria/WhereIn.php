<?php

namespace Platform\App\Repositories\Criteria;

use App\Repositories\Contracts\LibraryItemRepository;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class WhereIn extends Criteria
{

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public $attribute;
    /**
     * @var
     */
    public $value;
    /**
     * @var LibraryItemRepository
     */
    public $itemRepository;
    /**
     * @var string
     */


    /**
     * @param $attribute
     * @param $value
     * @param string $operator
     * @param LibraryItemRepository $itemRepository
     */
    function __construct( $attribute, $value)
    {
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply( $model, Repository $repository )
    {
        $query = $model->whereIn( $this->attribute, $this->value );

        return $query;
    }
}