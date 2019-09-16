<?php 
namespace Platform\Reports\TNA;

use Platform\Reports\AbstractReportBuilder;
use Platform\TNA\Models\TNA;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Transformers\TNATransformer;
use League\Fractal\Resource\Collection;

class TNAReportBuilder extends AbstractReportBuilder
{
  protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

  public function model(){
  	return new TNA;
  }

  public function filter($params)
  {
    $response = '';

        if(!isset($params['type']))$params['type'] = '';
        if(!isset($params['search']))$params['search'] = '';         
        $schema = $this->model()->transformSchema();

        if(is_array($params['search']) && count($params['search']) > 0){
            foreach($params['search'] as $criteria => $value){      
              $this->search($value,$criteria,$schema['filterable'],$schema['operation']);                             
            }
        }
        
        $this->appendTrash($params,$this->model);

        $response =  $this->basicSort($params,$schema);
        $total_results = $this->countResultData();
        $response = $this->basicPaginate($params,$response);    
        if(empty($response)) $response = $this;
        $output = $response->execute();
        $output = $this->applyTransformer($output,new TNATransformer);
        
   	return [
   		'schema' =>$this->model()->reportSchema(),
      'totalresult' => $total_results,
   		'tna'=> $output
   	];
   	
  }

   public function applyTransformer($data,$transformObj){  

        $transData = new Collection($data, $transformObj);
        return $this->fractal->createData($transData)->toArray()['data'];               
   }

   public function appendTrash($params,$qObj){
      if(array_key_exists('trash',$params)){
          if($params['trash']){
            $qObj = $qObj->withTrashed();  
          }              
        }
   }   
     
}


	 