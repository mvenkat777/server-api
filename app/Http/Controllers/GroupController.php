<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Groups\Commands\CreateGroupCommand;
use Platform\Groups\Commands\SearchAllGroupCommand;
use Platform\Groups\Commands\ShowGroupByIdCommand;
use Platform\Groups\Commands\UpdateGroupCommand;
use Platform\Groups\Transformers\GroupTransformer;
use Platform\Groups\Commands\DeleteGroupCommand;
use League\Fractal\Manager;

class GroupController extends ApiController
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
        $command=new SearchAllGroupCommand();

        $response=$this->commandBus->execute($command);

        //return $response;
        return $this->respondWithCollection($response, new GroupTransformer, 'Group' );
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

        $command=new CreateGroupCommand($formData);

        $response=$this->commandBus->execute($command);

        return $this->setMessage('Group Successfully Created.')
                    ->respondWithNewItem($response, new GroupTransformer , 'Group' );
        //return $this->respondOK('Group Successfully Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $command=new ShowGroupByIdCommand($id);

        $response=$this->commandBus->execute($command);
        
        if($response != null){
            return $this->respondWithItem($response, new GroupTransformer , 'Group');
        }else{
            return $this->setStatusCode(404)
                      ->respondWithError('Group not found', 'SE_40004');
        }
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

        $command=new UpdateGroupCommand($formData , $id);

        $response=$this->commandBus->execute($command);
        
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
        $command=new DeleteGroupCommand($id);

        $response=$this->commandBus->execute($command);

        return $this->respondOK($response);
    }
}
