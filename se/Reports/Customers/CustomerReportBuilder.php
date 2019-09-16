<?php 
namespace Platform\Reports\Customers;

use Platform\Reports\AbstractReportBuilder;
use App\Customer;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Transformers\CustomerTransformer;
use League\Fractal\Resource\Collection;

class CustomerReportBuilder extends AbstractReportBuilder
{
  protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

  public function model(){
  	return new Customer;
  }

  public function filter($params)
  {
   	//dd($params);

   	$response = '';

        if(!isset($params['type']))$params['type'] = '';
        if(!isset($params['search']))$params['search'] = '';         
        $schema = $this->model()->transformSchema();
   	    
        $miscCriteria = ['Type of Customer' => 'types:customer_customer_types.customer_type_id',
                          'Type of Service' => 'services:customer_customer_service.customer_service_id',
                          'Customer Requirement' => 'requirements:customer_customer_requirements.customer_requirement_id',
                          'Payment Terms' => 'paymentTerms:customer_customer_payment_terms.customer_payment_terms_id'
                        ];

        if(is_array($params['search']) && count($params['search']) > 0){
            foreach($params['search'] as $criteria => $value){
                if(array_key_exists($criteria ,$miscCriteria)){
                  if(is_array($value) && count($value) > 0){
                    $typeColumn = explode(":", $miscCriteria[$criteria]);
                    $this->searchPivotTableIds($value,$typeColumn[0],$typeColumn[1]);
                  }else{
                    throw new SeException("Invalid Inputs provided while advanced searching", 405, 7210103);
                  }
                }else{
                  $this->search($value,$criteria,$schema['filterable'],$schema['operation']); 
                }                
            }
        }

        $this->appendTrash($params,$this->model);

        $response =  $this->basicSort($params,$schema);
        $total_results = $this->countResultData();
        $response = $this->basicPaginate($params,$response);    
        //dd($response);
        if(empty($response)) $response = $this;
        //$this->setPivotTable(['tags','categories']);
        $output = $response->execute();

        $output = $this->applyTransformer($output,new CustomerTransformer);
        
   	return [
   		'schema' =>$this->model()->reportSchema(),
      'totalresult' => $total_results,
   		'customers'=> $output
   	];
   	
  }

   public function applyTransformer($data,$transformObj){  

        $transData = new Collection($data, $transformObj);
        return $this->fractal->createData($transData)->toArray()['data'];               
   }

   /*public function searchCustomerTypebyIds($typeIds){
    
    $this->model = $this->model->whereHas('types', function($query) use($typeIds){
                       $query->whereIn('customer_customer_types.customer_type_id',$typeIds);
                       });
    return $this; 
   }*/

   public function appendTrash($params,$qObj){
      if(array_key_exists('trash',$params)){
          if($params['trash']){
            $qObj = $qObj->withTrashed();  
          }              
        }
   }   
     
}


	 