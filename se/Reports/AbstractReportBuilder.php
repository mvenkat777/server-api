<?php namespace Platform\Reports;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;

abstract class AbstractReportBuilder
{

	protected $entity;

	protected $query;

	protected $criteria='';

    protected $table;

    protected $sOrder = ['chronological'=>'ASC','reverse_chronological'=>'DESC'];

 	protected function __construct()
    {
    	$this->model = $this->model();
    	$this->query = $this->model;
        $this->table = $this->model()->getTable();
    }

    abstract function model();

    abstract function filter($inputs);

    abstract function applyTransformer($data,$transformObj);

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    protected function getEntity($entity)
    {
        return $this->entity;
    }

    /**
     * Implement sorting of every entity
     * @param  [string] $sortcolumn [column name]
     * @param  [string] $sortorder  [ascending or descending]
     * @return [type]             [description]
     */
    protected function sort($sortColumnOrderValues,$transformArr)
    {
    	$sortColumnOrderValuesArray = explode(",", $sortColumnOrderValues);
    	foreach($sortColumnOrderValuesArray as $sortColumnOrder){
    		$sortColumnOrderArray = explode(".", $sortColumnOrder);
    		$sortColumn = $sortColumnOrderArray[0];
    		if(empty($sortColumnOrderArray[1])){ $sortColumnOrderArray[1] = 'chronological'; }
    		$sortOrder = $sortColumnOrderArray[1];

            if(isset($transformArr[$sortColumn])){
                $sortColumn = $transformArr[$sortColumn];

                if(isset($this->sOrder[$sortOrder])){
                    $sortOrder = $this->sOrder[$sortOrder];
                    $this->model = $this->model->orderBy($sortColumn, $sortOrder);
                }else{
                    throw new SeException('Invalid Sort Order given and Order needs to be chronological or reverse_chronological', 405, 7210101);
                }
            }else{
                throw new SeException('Transformation Mapping field not found while sorting', 405, 7210102);
            }

    	}
    	return $this;
    }

    /**
     * Search columns of every entity
     * @param  [array] $searcharr [$searcharr with key as columns and values as search inputs]
     * @return [type]            [description]
     */
    protected function search($paramQuery,$column,$transformArr,$operationArr)
    {
        if(isset($operationArr[$column])){ $operation = $operationArr[$column]; }else{ $operation = 'LIKE';}
    	if($operation == 'LIKE' || $operation == 'ILIKE') $paramQuery = '%'.$paramQuery.'%';
        if(isset($transformArr[$column])){
            $column = $transformArr[$column];
            if($column == 'tna_state_id'){
                $paramQuery = TNAHelper::getTNAStateId($paramQuery);
            }
            if($column == 'tna_health_id'){
                $paramQuery = TNAHelper::getTNAHealthId($paramQuery);
            }
            if($column == 'target_date' || $column == 'published_date' ||
              $column == 'projected_date' || $column == 'completed_date' || $column == 'start_date'){
                $this->model = $this->model->whereBetween($this->table.'.'.$column,
                    [Carbon::parse($paramQuery)->toDateString().' 00:00:00',
                    Carbon::parse($paramQuery)->toDateString().' 23:59:59']);
            }else{
                $this->model = $this->model->where($column, "$operation", $paramQuery);
            }
        }else{
            throw new SeException("Transformation Mapping field not found while searching", 405, 7210103);
        }
        return $this;
    }

    protected function searchDates($startDate,$endDate){
       if(empty($endDate)) $endDate = Carbon::now();

       $dateValidator = \Validator::make(['startDate'=>$startDate,'endDate'=>$endDate], [
            'startDate' => 'required|date|date_format:"Y-m-d H:i:s"',
            'endDate' => 'required|date|date_format:"Y-m-d H:i:s"',
       ]);

       if($dateValidator->passes()){
            $this->model = $this->model->whereBetween($this->table.'.created_at', array($startDate, $endDate));
       }else{
            throw new SeException("Invalid Dates given while searching with dates", 405, 7210104);
       }
       return $this;
    }


    /**
     * Get rows from pointer to number of rows
     * @param  [string] $from [rows staring from]
     * @param  [string] $rows   [total number of rows]
     * @return [type]       [description]
     */
	protected function paginate($rows,$from=0)
    {
        $this->model = $this->model->take($rows)->offset($from);
        return $this;
    }

    protected function basicSort($params,$schema){
        $response = '';

        if(array_key_exists('orderby',$params) ){
            $response = $this->sort($params['orderby'],$schema['sortable']);
        }

        return $response;
    }

    protected function basicPaginate($params,$response){

        if(array_key_exists('results',$params) ){
            if(empty($params['from'])){ $params['from'] = 0; }
            $response = $this->paginate($params['results'],$params['from']);
        }

        return $response;
    }

    protected function searchPivotTableIds($idArray,$pivotModel,$colName){

        $this->model = $this->model->whereHas($pivotModel, function($query) use($idArray,$colName){
                           $query->whereIn($colName,$idArray);
                           });
        return $this;
   }

    protected function setPivotTable($pivotArr){
        $this->model = $this->model->with($pivotArr);
        return $this;
    }
    protected function countResultData() {
        return $this->model->get()->count();
    }

    protected function execute() {
    	return $this->model->get();
    }
}
