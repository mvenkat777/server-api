<?php 
namespace Platform\Reports\Users;

use Platform\Reports\AbstractReportBuilder;
use App\User;
use App\UserTag;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Users\Transformers\UserTagTransformer;
use Platform\Users\Transformers\UserTransformer;
use League\Fractal\Resource\Collection;

class UserReportBuilder extends AbstractReportBuilder
{
     protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

    public function model(){
    	return new User;
    }

     public function filter($params)
     {
     	//dd($params);
     	$response = '';
          if(!isset($params['type']))$params['type'] = '';         
          if(!isset($params['search']))$params['search'] = '';         
          $schema = $this->model()->transformSchema();
          // dd($schema);   
          if(is_array($params['search']) && count($params['search']) > 0){
              foreach($params['search'] as $criteria => $value){
                  if($criteria == 'Date'){
                    if(!isset($value[1])){ $value[1] = '';}
                    $this->searchDates($value[0],$value[1]);  
                  }elseif($criteria == 'Tag'){                   
                    $this->searchTagbyName($value);
                  }else{
                    $this->search($value,$criteria,$schema['filterable'],$schema['operation']);
                  }
              }
          }
     	
          $response =  $this->basicSort($params,$schema);
          $total_results = $this->countResultData();
          $response = $this->basicPaginate($params,$response);   	
          
          if(empty($response)) $response = $this;
          $this->setPivotTable(['tags']);
          $output = $response->execute();
          //dd($output);
          $output = $this->applyTransformer($output,new UserTransformer);
                        
     	return [
     		'schema' =>$this->model()->reportSchema(),
               'totalresult' => $total_results,
     		'users'=> $output
     	];
     	
     }

     public function applyTransformer($data,$transformObj){  
          //dd($data->toArray()[0]['tags']);
          $transData = new Collection($data, $transformObj);
          return $this->fractal->createData($transData)->toArray()['data'];               
     }

     public function searchTagbyName($q){
          $tagNamesArray = $q;
          //dd($tagNamesArray);
          
          /*$users = \App\User::with(['tags' => function ($query) use($tagIdsArray) {
               $query->whereIn('tag_id', [1,2]);
          }])->get();
          dd($users[0]->tags);*/

          //ALTERANTIVE JOIN QUERY
          //dd($this->model->toSql());
          $this->model = $this->model->join('user_user_tag','users.id','=','user_user_tag.user_id')
               ->join('user_tag','user_tag.id','=','user_user_tag.tag_id')
               ->where(function($query1) use ($tagNamesArray){
                    foreach($tagNamesArray as $tagName){   
                      $query1->orWhere('user_tag.name','=',$tagName);
                    }
               })
               //->whereIn('user_tag.id',$tagIdsArray)
               ->select(\DB::raw('distinct on (users.id) users.id') ,'users.id','display_name','email','reset_pin','is_banned','is_active','users.created_at','users.updated_at','se','last_login_location','is_password_change_required','user_tag.id as tag_id','name as tag_name','user_tag.created_at as tag_created_at','user_tag.updated_at as tag_updated_at')                    
               ->orderBy('users.id');
          //dd($user_tags->with('tags')->get()->toArray());*/
          //ALTERANTIVE WHER HAS
         /* $users = $this->model->whereHas('tags', function($query) use($tagNamesArray){
               $query->where(function($query1) use ($tagNamesArray){
                    foreach($tagNamesArray as $tagName){   
                      $query1->orWhere('name','=',$tagName);
                    }
               });
          })->toSql();
          */
           
          return $this;

     }
     
}     

	 