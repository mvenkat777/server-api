<?php

namespace Platform\Help\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Help\Commands\CreateHelpCommand;
use Platform\Help\Repositories\Contracts\HelpRepository;
use Vinkla\Hashids\HashidsManager;
use DB;




class EloquentHelpRepository extends Repository implements HelpRepository
{ 

    public function model()
    {
        return 'App\Help';
    }


    public function getAppsList()
    {
        $data= \App\AppsList::whereNotIn('app_name', ['admin', 'rule','samplecontainer'])->get();
        // dd($data);
        return $data;
    }

    public function getTopicByAppId($command)
    {
        $appid = DB::table('apps_list')->where('app_name',$command->app_name)->value('id');
        $data= $this->model->where('app_id',$appid)->get();
        
          return $data;
    }
    
    
    public function makeHelp(CreateHelpCommand $command)
    {   
        $help = $this->model->where('slug', $command->slug)->first();

        
        if ($help) {
            throw new SeException("slug already exists", 422, 4220990);
        }

        $app = \App\AppsList::where('app_name', $command->app)->first();
        
        if (!$app) {
            throw new SeException("App Does Not Exists", 422, 4220991);
        } 

        $data = [
            'id' => $this->generateUUID(),
            'slug' => $command->slug,
            'title' => $command->title,
            'description' => $command->description,
            'owner' => json_encode(\Auth::user()),
            'author_log' => json_encode(\Auth::user()),
            // 'feedback' => json_encode($command->feedback),
            'app_id' => $app->id,
            'like' => 0,
            'dislike' => 0,
        ];
       // dd($data);
        \DB::beginTransaction();
        $help = $this->model->create($data);
         // dd($help);
        \DB::commit();

        return $help; 
        
    }


    /**
     * @param showHelpContent $command
     * @return all
     */

    public function showHelpBySlug($command)
    {
        $help = $this->model->where('slug', $command->slug)->first();
        if (!$help) {
            throw new SeException("Invalid Slug ", 422, 4220992);
        }
        return $help;

    }
    

     public function destroy($command)
      {
        return $this->model->where('slug','=',$command->slug)->delete();

      }
   
    /**
     * @param UpdateHelp $command
     * @return updated
     */

    public function updateHelp($command)
    {
        $help = $this->model->where('slug','=',$command->slug)->first();
        if(is_null($help))
        {
            return 0;
        }
        $helpDetail = [
            'title' => $command->title,
            'description' => $command->description,
            'author_log' => json_encode(\Auth::user()),
        ]; 
        return  $this->model->where('slug','=',$command->slug)->update($helpDetail);
    }
       /** 
        *   @param AddLike 
        *   @return return updated like
        */
    
    public function addLike($command)
    {

        $help = $this->model->where('slug','=',$command->slug)->first();
        if(is_null($help))
        {
            return 0;
        } 

        $alreadyLiked = \DB::table('help_like')->where('user_id', \Auth::user()->id)
            ->where('help_id', $help->id)->first();

        if ($alreadyLiked) {
            throw new SeException("Already Done", 422, 4040351);
        }

        $helpDetail = [
            'user_id' => \Auth::user()->id,
            'help_id' => $help->id,
            'is_like' => $command->like
        ]; 

        DB::table('help_like')->insert($helpDetail);
        
        $likes_count = DB::table('help_like')->where('help_id', $help->id)->where('is_like', TRUE)->count();
    
        $dislikes_count = DB::table('help_like')->where('help_id', $help->id)->where('is_like', FALSE)->count();
        $helpUpdate = [
            'like' => $likes_count,
            'dislike' => $dislikes_count
        ];
        
        return  $this->model->where('slug','=',$command->slug)->update($helpUpdate);
    }
      
    
     
     /** 
        *   @param AddFeedback
        *   @return return 0
        */
    public function addFeedback($command)
    {
        $help = $this->model->where('slug','=',$command->slug)->first();
        
        if(is_null($help))
        {
            return 0;
        }
        $helpDetail = [
            'feedback' => json_encode($command->feedback)
        ]; 
        
        return  $this->model->where('slug','=',$command->slug)->update($helpDetail);
    }

}
