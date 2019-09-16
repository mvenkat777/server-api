<?php

namespace Platform\App\Repositories\Criteria;

use App\Commands\LibraryItem\GetLibraryItemBySlug;
use Platform\App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\LibraryItemRepository;
use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

/**
 * Class BelongsToLibraryItem
 * @package App\Repositories\Criteria
 */
class BelongsToLibraryItemSlug extends Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public $libraryItemSlug;
    public $libraryItemId;
    public $itemRepository;

    /**
     * @param $libraryItemId
     */
    public function __construct($libraryItemSlug, LibraryItemRepository $itemRepository)
    {
        $this->libraryItemSlug = $libraryItemSlug;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        dd('aa');
        dd($this->itemRepository->getLibraryItemBySlug(new GetLibraryItemBySlug($this->libraryItemSlug)));
        $query = $model->where('library_item_id', '=', $this->libraryItemId);

        return $query;
    }
}
