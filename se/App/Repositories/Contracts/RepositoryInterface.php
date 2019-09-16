<?php

namespace Platform\App\Repositories\Contracts;

/**
     * Interface RepositoryInterface
     * @package App\Repositories\Contracts
     */
/**
 * Interface RepositoryInterface
 * @package App\Repositories\Contracts
 */
interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'));

    /**
     * @param $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = array('*'));

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id);

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findTrashed($id, $columns = array('*'));

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findTrashedOnly($id, $columns = array('*'));

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'));

    public function findTrashedBy($field, $value, $columns = array('*'));
}
