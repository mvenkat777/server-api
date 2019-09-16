<?php 
namespace Platform\Reports\Tasks;

use Platform\Reports\AbstractReportBuilder;
use App\Task;
use App\Tag;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Transformers\TagTransformer;
use Platform\Tasks\Transformers\TaskTransformer;
use League\Fractal\Resource\Collection;

class TaskReportBuilder extends AbstractReportBuilder
{
     protected $fractal;
	public function __construct()
	{
	  parent::__construct();
       $this->fractal = new Manager();
	}

    public function model(){
    	return new Task;
    }

     public function filter($params)
     {
     	//dd($params);

     	$response = '';

          if(!isset($params['type']))$params['type'] = '';
          if(!isset($params['search']))$params['search'] = '';         
          $schema = $this->model()->transformSchema();
     	    
          if(is_array($params['search']) && count($params['search']) > 0){
              foreach($params['search'] as $criteria => $value){
                  if($criteria == 'Category'){
                    $this->searchCategorybyName($value);
                  }elseif($criteria == 'Tag'){
                    $this->searchTagbyName($value);
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
          $this->setPivotTable(['tags','categories']);
          $output = $response->execute();

          $output = $this->applyTransformer($output,new TaskTransformer);
          
     	return [
     		'schema' =>$this->model()->reportSchema(),
        'totalresult' => $total_results,
     		'tasks'=> $output
     	];
     	
     }

     public function applyTransformer($data,$transformObj){  

          $transData = new Collection($data, $transformObj);
          return $this->fractal->createData($transData)->toArray()['data'];               
     }

     public function searchTagbyName($params){
          $tagNamesArray = $params;

        /*  $this->model = $this->model->join('task_tag_task','tasks.id','=','task_tag_task.task_id')
               ->join('task_tags','task_tags.id','=','task_tag_task.tag_id')
               ->where(function($query1) use ($tagNamesArray){
                    foreach($tagNamesArray as $tagName){   
                      $query1->orWhere('task_tags.title','=',$tagName);
                    }
               })
               //->whereIn('task_tags.id',$tagIdsArray)
               ->select(\DB::raw('distinct on (tasks.id) tasks.id') ,'tasks.id','creator_id','tasks.title','description','due_date','priority_id','assignee_id','status_id','tasks.created_at','tasks.updated_at','task_tags.id as tag_id','task_tags.title as tag_name','task_tags.created_at as tag_created_at','task_tags.updated_at as tag_updated_at')                    
               ->orderBy('tasks.id');
        */
        
        $this->model = $this->model->whereHas('tags', function($query) use($tagNamesArray){
                       $query->where(function($query1) use ($tagNamesArray){
                                      foreach($tagNamesArray as $tagName){   
                                        $query1->orWhere('task_tags.title','=',$tagName);
                                      }
                                    });
                       });
          return $this;     
     }

     public function searchCategorybyName($params){
         //dd($params);
          $catNamesArray = $params;

          /*$this->model = $this->model()->join('task_category_task','tasks.id','=','task_category_task.task_id')
                              ->join('task_categories','task_categories.id','=','task_category_task.category_id')
                              ->where(function($query) use ($catNamesArray) 
                                {
                                    foreach($catNamesArray as $catName){   
                                      $query->orWhere('task_categories.title','=',$catName);
                                    }
                                })
                              ->select(\DB::raw('distinct on (tasks.id) tasks.id') ,'tasks.id','creator_id','tasks.title','description','due_date','priority_id','assignee_id','status_id','tasks.created_at','tasks.updated_at','task_categories.id as category_id','task_categories.title as category_name')
                              ->orderBy('tasks.id');
          */
           $this->model = $this->model->whereHas('categories', function($query) use($catNamesArray){
                       $query->where(function($query1) use ($catNamesArray){
                                      foreach($catNamesArray as $catName){   
                                        $query1->orWhere('task_categories.title','=',$catName);
                                      }
                                    });
                       });

          return $this;          
     
     }    

     /*public function searchTagbyName($params){
         //dd($params);
          $tagNamesArray = explode(",", $params['query']);

          $task_tags = $this->model()->join('taskTagTask','tasks.id','=','taskTagTask.taskId')
                              ->join('taskTags','taskTags.id','=','taskTagTask.tagId');

          
            $task_tags->where(function($query) use ($tagNamesArray) 
            {
                foreach($tagNamesArray as $tagName){   
                  $query->orWhere('taskTags.title','=',$tagName);
                }
            });                   
          $this->appendTrash($params,$task_tags);
          $task_tags->select('tasks.id','creatorId','tasks.title','description','dueDate','priorityId','assigneeId','statusId','tasks.created_at','tasks.updated_at');
          //dd($task_tags->toSql());
          $total_results = $task_tags->get()->count();            
          if(array_key_exists('results',$params) ){
               if(empty($params['from'])){ $params['from'] = 0; }
               $task_tags = $task_tags->take($params['results'])->offset($params['from']);
          }
          
          $data = $task_tags->with('tags')->get();

          //$total_results = $user_tags_np->with('tags')->get()->count();
          //dd($data);
          $output = $this->applyTransformer($data,new TaskTransformer);
                                  
          return [
               'schema' =>$this->model->reportSchema(),
               'totalresult' => $total_results, 
               'tasks'=> $output
          ];               
         
     }
      
      public function searchCategorybyName($params){
         //dd($params);
          $catNamesArray = explode(",", $params['query']);

          $task_cats = $this->model()->join('taskCategoryTask','tasks.id','=','taskCategoryTask.taskId')
                              ->join('taskCategories','taskCategories.id','=','taskCategoryTask.categoryId');

          
            $task_cats->where(function($query) use ($catNamesArray) 
            {
                foreach($catNamesArray as $catName){   
                  $query->orWhere('taskCategories.title','=',$catName);
                }
            });                   
          $this->appendTrash($params,$task_cats);
          $task_cats->select('tasks.id','creatorId','tasks.title','description','dueDate','priorityId','assigneeId','statusId','tasks.created_at','tasks.updated_at');
          
          $total_results = $task_cats->get()->count();            
          if(array_key_exists('results',$params) ){
               if(empty($params['from'])){ $params['from'] = 0; }
               $task_cats = $task_cats->take($params['results'])->offset($params['from']);
          }
          
          $data = $task_cats->with('tags')->get();

          //$total_results = $user_tags_np->with('tags')->get()->count();
          //dd($data);
          $output = $this->applyTransformer($data,new TaskTransformer);
                                  
          return [
               'schema' =>$this->model->reportSchema(),
               'totalresult' => $total_results, 
               'tasks'=> $output
          ];               
         
     } 
    */
     public function appendTrash($params,$qObj){
        if(array_key_exists('trash',$params)){
            if($params['trash']){
              $qObj = $qObj->withTrashed();  
            }              
          }
     }   
     
}


	 