<?php 
namespace Platform\Reports\Vendors;

use Platform\Reports\AbstractReportBuilder;
use App\Vendor;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Transformers\VendorTransformer;
use League\Fractal\Resource\Collection;

class VendorReportBuilder extends AbstractReportBuilder
{
  protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

  public function model(){
  	return new Vendor;
  }

  public function filter($params)
  {
   	//dd($params);

   	$response = '';

        if(!isset($params['type']))$params['type'] = '';
        if(!isset($params['search']))$params['search'] = '';         
        $schema = $this->model()->transformSchema();
   	    
        $miscCriteria = ['Type of Vendor' => 'types:vendor_vendor_types.vendor_type_id',
                          'Type of Service' => 'services:vendor_vendor_service.vendor_service_id',
                          'Payment Terms' => 'paymentTerms:vendor_vendor_payment_terms.vendor_payment_terms_id'
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

        $output = $this->applyTransformer($output,new VendorTransformer);
        
   	return [
   		'schema' =>$this->model()->reportSchema(),
      'totalresult' => $total_results,
   		'vendors'=> $output
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


	 