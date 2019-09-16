<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Apps\Commands\CreateAppCommand;
use Platform\Apps\Commands\SearchAllAppCommand;
use Platform\Apps\Commands\ShowAppByIdCommand;
use Platform\Apps\Commands\UpdateAppCommand;
use Platform\Apps\Commands\DeleteAppCommand;
use Platform\Apps\Commands\SearchAllPermsCommand;
use Platform\Apps\Transformers\AppTransformer;
use Platform\Apps\Transformers\PermissionTransformer;
use League\Fractal\Manager; 

class AppController extends ApiController
{

    /**
     * @var Platform\Commands\DefaultCommandBus
     */
    protected $commandBus;


    function __construct(DefaultCommandBus $commandBus)
    {
        // $this->middleware('auth.token');
        $this->commandBus = $commandBus;
        parent::__construct(new Manager());
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $command=new SearchAllAppCommand();

        $response=$this->commandBus->execute($command);
        // return $response;
        return $this->respondWithCollection($response , new AppTransformer , 'App');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $formData=$request->all();

        $formData['token'] = $request->header('access-token');

        $command=new CreateAppCommand($formData);
        //dd($command);
        $response=$this->commandBus->execute($command);
        //dd($response);
        return $this->setMessage('App created successfully.')
                     ->respondWithNewItem($response, new AppTransformer , 'App' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $command=new ShowAppByIdCommand($id);

        $response=$this->commandBus->execute($command);
        //dd($response);
        if(is_object($response))
        return $this->respondWithItem($response, new AppTransformer , 'App');
        else
        return $this->respondOK($response);    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $formData=$request->all();

        $command=new UpdateAppCommand($formData , $id);

        $response=$this->commandBus->execute($command);
        //return $response;

        return $this->respondOK($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $command=new DeleteAppCommand($id);

        $response=$this->commandBus->execute($command);
        
        return $this->respondOK($response);
    }


    public function getAllPermissions(){

        $command=new SearchAllPermsCommand();

        $response=$this->commandBus->execute($command);
        //dd($response->toArray());
        return $this->respondWithCollection($response , new PermissionTransformer , 'Permission');
    }
}
