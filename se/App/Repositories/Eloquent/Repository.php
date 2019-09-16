<?php

namespace Platform\App\Repositories\Eloquent;

use Platform\App\Repositories\Contracts\CriteriaInterface;
use Platform\App\Repositories\Criteria\Criteria;
use Platform\App\Repositories\Contracts\RepositoryInterface;
use Platform\App\Repositories\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Rhumsaa\Uuid\Uuid;
use Carbon\Carbon;

/**
 * Class Repository
 * @package App\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface, CriteriaInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var boolean
     */
    protected $isSorted = false;

    /**
     * @param App $app
     * @param Collection $collection
     * @param Request $request
     * @throws RepositoryException
     */
    public function __construct(App $app, Collection $collection, Request $request)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
        $this->request = $request;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->get($columns);
    }

    /**
     * @param  string $value
     * @param  string $key
     * @return array
     */
    public function lists($value, $key = null)
    {
        $this->applyCriteria();

        return $this->model->lists($value, $key);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginateAll($perPage = 1, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->withTrashed()->paginate($perPage, $columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginateTrashed($perPage = 1, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->onlyTrashed()->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->first()->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function archive($id)
    {
        return $this->model->find($id)->update(['archived_at' => Carbon::now()]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function rollback($id)
    {
        return $this->model->find($id)->update(['archived_at' => null]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->model->onlyTrashed()->find($id)->restore();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->find($id, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findTrashed($id, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->withTrashed()->find($id, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findTrashedOnly($id, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->onlyTrashed()->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findTrashedBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();

        return $this->model->onlyTrashed()->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);

        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);

        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Checks the validity of a uuid
     *
     * @param string $string
     */
    public function isValidUUID($string)
    {
        return Uuid::isValid($string);
    }


    public function attach($model_id, $relation, $relation_ids, $attributes=[])
    {
        return ($this->find($model_id)->$relation()->attach($relation_ids, $attributes));
    }

    public function detach($model_id, $relation, $relation_ids)
    {
        return ($this->find($model_id)->$relation()->detach($relation_ids));
    }

    public function associations($model_id, $relation)
    {
        return ($this->find($model_id)->$relation);
    }

    public function associationsRaw($table, $model, $model_id)
    {
        return \DB::table($table)->where($model.'_id', $model_id)->get();
    }

    /**
     * Filter the model data
     * @param  array $request
     * @return $this->model
     */
    public function filter($request)
    {
        $searchOperations = isset($this->model->searchable)? $this->model->searchable : [];
        $foreignFields = isset($this->model->foreign)? $this->model->foreign : [];
        $pivotFields = isset($this->model->pivots)? $this->model->pivots : [];
        $metaFields = isset($this->model->metaFields)? $this->model->metaFields : [];

        if ($metaFields) {
            $model = $this->model->select($metaFields);
        } else {
            $model = $this->model;
        }

        foreach ($request as $key => $value) {
            if ($key == 'q') {
                $model = $this->globalSearch($model, $value);
            } else if ($key == 'sort') {
                $model = $this->sort($model, $value);
            } else if (array_key_exists($key, $searchOperations)) {
                $operation = $searchOperations[$key];
                $model = $this->searchFilter($model, $value, $operation);
            } else if (array_key_exists($key, $foreignFields)) {
                $linked = $foreignFields[$key];
                $model = $this->searchForeign($model, $value, $linked);
            } else if (array_key_exists($key, $pivotFields)) {
                $pivot = $pivotFields[$key];
                $model = $this->searchByPivot($model, $value, $pivot);
            } else if ($key == 'archived'){
                $model = $this->searchArchive($model, $value);
            } elseif ($key == 'completed') {
                $model = $this->searchComplete($model, $value);
            }
        }

        if(!$this->isSorted){
            $model = $model->orderBy('updated_at', 'desc');
        }
        return $model;
    }

    /**
     * Global search for filtering
     * @param  object $model
     * @param  string $value
     * @return object
     */
    private function globalSearch($model, $value)
    {
        $globalSearchColumns = $this->model->globalSearchColumns;
        
        $model = $model->where(function ($query) use ($globalSearchColumns, $value) {
            $query->where($globalSearchColumns[0], 'ILIKE', "%$value%");
            foreach (array_splice($globalSearchColumns, 1, count($globalSearchColumns)) as $column) {
                $query->orWhere($column, 'ILIKE', "%$value%");
            }
        });

        return $model;
    }

    /**
     * Sort for filtering
     * @param  object $model
     * @param  string $value
     * @return object
     */
    private function sort($model, $value)
    {
        $sortableFields = isset($this->model->sortable)? $this->model->sortable : [];
        $sortParameters = explode('.', $value);

        if (count($sortParameters) == 2 && array_key_exists($sortParameters[0], $sortableFields)) {
            $this->isSorted = true;
			$column = $sortableFields[$sortParameters[0]];
			$order = $sortParameters[1];	
            return $model->orderByRaw("$column $order");
        }
        return $model->orderBy('updated_at', 'desc');
    }

    /**
     * Search for filtering
     * @param  object $model
     * @param  string $value
     * @param  array $operation
     * @return object
     */
    private function searchFilter($model, $value, $operation)
    {
		$value = str_replace("'", "''", $value);

        if ($operation['operation'] == 'ILIKE') {
			$column = $operation['column'];
            return $model->whereRaw("$column ILIKE '%$value%'");
        }

        if ($operation['operation'] == '=') {
            return $model->where($operation['column'], '=', $value);
        }

        if ($operation['operation'] == 'date') {
            $date = \Carbon\Carbon::parse($value)->toDateString();
            return $model->whereBetween(
                $operation['column'],
                [$date.' 00:00:00', $date.' 23:59:59']
            );
        }

        if ($operation['operation'] == 'between') {
            return $model->whereBetween(
                $operation['column'],
                json_decode($value)
            );
        }
    }

    /**
     * Search in foreign data for filtering
     * @param  object $model
     * @param  string $value
     * @param  array $linked
     * @return object
     */
    private function searchForeign($model, $value, $linked)
	{

        if($linked['operation'] == 'between'){
            $ids = (new $linked['relation'])->whereBetween(
            $linked['foreignField'],
            $value)->lists('id');  
        }else{
            //$value = str_replace("'", "''", $value);
            $ids = (new $linked['relation'])->where(
            $linked['foreignField'],
            $linked['operation'],
            ($linked['operation'] == '=')?$value:"%$value%")->lists('id');            
        }

        return $model->whereIn($linked['modelField'], $ids);
        
    }

    /**
     * Search through pivot table for filtering
     * @param  object $model
     * @param  string $value
     * @param  array $pivot
     * @return object
     */
    private function searchByPivot($model, $value, $pivot)
    {
		$value = str_replace("'", "''", $value);

		$ids = (new $pivot['relation'])->where(
            $pivot['relationField'],
            $pivot['operation'],
            "%$value%"
        )->lists('id');

        $resultIds = (new $pivot['pivotTable'])->whereIn(
            $pivot['pivotSearchField'],
            $ids
        )->lists($pivot['pivotResultField']);

        return $model->whereIn($pivot['modelField'], $resultIds);
    }

    /**
     * Search for archived ones for filtering
     * @param  object $model
     * @param  string $value
     * @return object
     */
    private function searchArchive($model, $value)
    {
        if($value == "true"){
           return $model->whereNotNull('archived_at');
        }else{
            return $model->whereNull('archived_at');
        }
    }

    /**
     * Search Completed
     * @param  query $model 
     * @param  boolean $value 
     * @return query
     */
    private function searchComplete($model, $value)
    {
        if($model->getModel()->getTable() != 'tna'){
            if($value == "true"){
               return $model->whereNotNull('completed_at');
            }else{
                return $model->whereNull('completed_at');
            }
        } else {
            if($value == "true"){
               return $model->whereNotNull('completed_date');
            }else{
                return $model->whereNull('completed_date');
            }
        }
    }
}
