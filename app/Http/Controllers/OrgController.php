<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Orgs\Commands\CreateOrgCommand;
use Platform\Orgs\Commands\SearchAllOrgCommand;
use Platform\Orgs\Commands\ShowOrgByIdCommand;
use Platform\Orgs\Commands\UpdateOrgCommand;
use Platform\Orgs\Commands\DeleteOrgCommand;
use Platform\Orgs\Transformers\OrgTransformer;

class OrgController extends ApiController
{

     /**
     * @var Platform\Commands\DefaultCommandBus
     */
    protected $commandBus;


    function __construct(DefaultCommandBus $commandBus)
    {
        // $this->middleware('auth.token');
        $this->commandBus = $commandBus;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $command=new SearchAllOrgCommand();

        $response=$this->commandBus->execute($command);

        return $response;
        return $this->respondWithCollection($response, new OrgTransformer, 'Group' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData=$request->all();
        
        $command=new CreateOrgCommand($formData);

        $response=$this->commandBus->execute($command);

        return $this->respondWithArray([$response]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command=new ShowOrgByIdCommand($id);

        $response=$this->commandBus->execute($command);
        return $response;
        return $this->respondWithArray((array)$response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formData=$request->all();

        $command=new UpdateOrgCommand($formData , $id);

        $response=$this->commandBus->execute($command);
        return $response;

        return $this->respondWithArray((array)$response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command=new DeleteOrgCommand($id);

        $response=$this->commandBus->execute($command);
        return $response;
    }
}
