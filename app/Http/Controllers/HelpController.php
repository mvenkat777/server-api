<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Help\Commands\AddDislikeCommand;
use Platform\Help\Commands\AddFeedbackCommand;
use Platform\Help\Commands\AddLikeCommand;
use Platform\Help\Commands\Command;
use Platform\Help\Commands\CreateHelpCommand;
use Platform\Help\Commands\GetFeedbackBySlugCommand;
use Platform\Help\Commands\ShowHelpBySlugCommand;
use Platform\Help\Commands\UpdateContactCommand;
use Platform\Help\Commands\UpdateHelpBySlugCommand;
use Platform\Help\Repositories\Contracts\HelpRepository;
use Platform\Help\Transformers\AppsListTransformer;
use Platform\Help\Transformers\AppsListWithHelpTransformer;
use Platform\Help\Transformers\HelpTransformer;
use Platform\Help\Commands\DeleteHelpBySlugCommand;
use Platform\Help\Commands\GetAllTopicByAppIdCommand;
use Platform\Help\Transformers\GetTopicsWithHelpTransformer;
use Platform\Help\Transformers\LikeAndDislikeTransformer;

class HelpController extends ApiController
{
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $commandBus;

    public function __construct(DefaultCommandBus $commandBus, HelpRepository $help)
    {
        $this->commandBus = $commandBus;
        $this->help = $help;
        parent::__construct(new Manager());
    }
    /**  
     * @return string
     */
    public function index()
    {
        $app = $this->help->getAppsList();
        
        return $this->respondWithCollection($app, new AppsListWithHelpTransformer, 'app');
    }

    public function getTopicByAppId($app_name)
    {    
       $command = new GetAllTopicByAppIdCommand($app_name);
       // dd($command);
        
        $response = $this->commandBus->execute($command);
        // dd($response);
       
        $data= $this->respondWithCollection($response, new GetTopicsWithHelpTransformer, 'response');
        return $data;
       
    }
     /**  
      * @return string
      */
    public function getAllApp()
    { 
        $app = $this->help->getAppsList();

        return $this->respondWithCollection($app, new AppsListTransformer, 'app');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
         $formData =$request->all();
        $command = new CreateHelpCommand($formData);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response, new HelpTransformer, 'help');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $command=new ShowHelpBySlugCommand($slug);
        $response=$this->commandBus->execute($command);
        if ($response) {
            return $this->respondWithItem($response, new HelpTransformer, 'help');
        }
        return $respondWithError('Help Creation Failed');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Slug)
    {
        $formData = $request->all();
        $command=new UpdateHelpBySlugCommand($formData, $Slug);
        $response=$this->commandBus->execute($command);
        if ($response) {
            return $this->respondOk('Help Updated successfully');
        }
        return $this->respondWithError('Faild To update');

    } 
    public function deleteHelp($slug)
    {
       
        $result = $this->commandBus->execute(new DeleteHelpBySlugCommand($slug));
        if($result){
            return $this->respondOk('Help is deleted successfully');
        }
        else{
            return $this->setStatusCode(500)
                ->respondWithError('Help could not be deleted', 50000);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */

    public function like(Request $request, $slug)
    {
        $formData =$request->all();
        $command = new AddLikeCommand($formData, $slug);
        $response = $this->commandBus->execute($command);
        if ($response) {
            return $this->respondWithItem(\App\Help::where('slug', $slug)->first(), new LikeAndDislikeTransformer, 'help');
        }

    }
      /**
     * Add the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */

    public function dislike(Request $request, $slug)
    {
        $formData =$request->all();
        $command = new AddDislikeCommand($formData, $slug);
        $response = $this->commandBus->execute($command);
        if ($response) {
            return $this->respondOk('Disliked successfully');
        }
        return $this->respondWithError('Faild To update');
    }
      /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function feedbackStore(Request $request, $slug)
    {
        $formData =$request->all();
        $command = new AddFeedbackCommand($formData, $slug);
        $response = $this->commandBus->execute($command);

        if ($response) {
            return $this->respondOk('Feedback Added successfully');
        }
        return $this->respondWithError('Faild To Add Feedback');
    }

   
}   