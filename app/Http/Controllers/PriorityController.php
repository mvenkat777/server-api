<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Priority\Commands\CreatePriorityCommand;
use Platform\Priority\Commands\SearchAllPriorityCommand;
use Platform\Priority\Commands\ShowPriorityByIdCommand;
use Platform\Priority\Commands\UpdatePriorityCommand;
use Platform\Priority\Commands\DeletePriorityCommand;
use Platform\Priority\Transformers\PriorityTransformer;
use League\Fractal\Manager; 

class PriorityController extends ApiController
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
        $command=new SearchAllPriorityCommand();

        $response=$this->commandBus->execute($command);
        //dd($response->toArray());
        // return $response;
        return $this->respondWithCollection($response , new PriorityTransformer , 'Priority');
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
     *;
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // $formData=$request->all();
        // $formData = array($formData);
        $send_data = ['priority' => $request->priority];
        $command=new CreatePriorityCommand($send_data);

        $response=$this->commandBus->execute($command);

        return $this->respondWithNewItem($response, new PriorityTransformer , 'Priority' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $command=new ShowPriorityByIdCommand($id);

        $response=$this->commandBus->execute($command);
        
        return $this->respondWithItem($response, new PriorityTransformer , 'Priority');
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
        //dd($id);
        $command=new UpdatePriorityCommand($formData , $id);

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
        $command=new DeletePriorityCommand($id);

        $response=$this->commandBus->execute($command);
        
        return $this->respondOK($response);
    }
}
