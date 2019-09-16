<?php 
namespace Platform\Reports\Orders;

use Platform\Reports\AbstractReportBuilder;
use App\Order;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Orders\Transformers\OrderTransformer;
use League\Fractal\Resource\Collection;

class OrderReportBuilder extends AbstractReportBuilder
{
  protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

  public function model(){
  	return new Order;
  }

  public function filter($params)
  {
   	//dd($params);

   	$response = '';

        if(!isset($params['type']))$params['type'] = '';
        if(!isset($params['search']))$params['search'] = '';         
        $schema = $this->model()->transformSchema();

        //$this->model = $this->model->join('customers','customers.id','=','orders.customer_id');
   	    
        /*$miscCriteria = ['Type of Customer' => 'types:vendor_vendor_types.vendor_type_id',
                          'Type of Service' => 'services:vendor_vendor_service.vendor_service_id',
                          'Payment Terms' => 'paymentTerms:vendor_vendor_payment_terms.vendor_payment_terms_id'
                        ];
        */
        if(is_array($params['search']) && count($params['search']) > 0){
            foreach($params['search'] as $criteria => $value){
              if($criteria == 'Customer Name'){
                    $this->model = $this->model->whereIn('orders.customer_id',$value);
              }elseif($criteria == 'Vendor'){
                    $this->searchPivotTableIds($value,'vendors','order_vendors.vendor_id');
                    //$this->model = $this->model->select('orders.*','customers.*','vendors.*');        
              }else          
              $this->search($value,$criteria,$schema['filterable'],$schema['operation']);                             
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

        $output = $this->applyTransformer($output,new OrderTransformer);
        
   	return [
   		'schema' =>$this->model()->reportSchema(),
      'totalresult' => $total_results,
   		'orders'=> $output
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


	 