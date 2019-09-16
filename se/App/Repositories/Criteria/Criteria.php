<?php

namespace Platform\App\Repositories\Criteria;

use Platform\App\Repositories\Contracts\RepositoryInterface as Repository;
use Platform\App\Repositories\Contracts\RepositoryInterface;

abstract class Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    abstract public function apply($model, Repository $repository);
}
