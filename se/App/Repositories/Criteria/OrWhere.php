<?php

namespace Platform\App\Repositories\Criteria;

use App\Repositories\Contracts\LibraryItemRepository;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class OrWhere extends Criteria
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
    public $operator;


    /**
     * @param $attribute
     * @param $value
     * @param string $operator
     * @param LibraryItemRepository $itemRepository
     */
    public function __construct($attribute, $value, $operator = '=')
    {
        $this->attribute = $attribute;
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $query = $model->orWhere($this->attribute, $this->operator, $this->value);

        return $query;
    }
}
