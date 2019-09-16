<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Platform\App\Commanding\DefaultCommandBus;

use Platform\Tasks\Commands\AddCheckListCommand;
use Platform\Tasks\Commands\EditCheckListCommand;
use Platform\Tasks\Commands\DeleteCheckListCommand;
use Platform\Tasks\Commands\CompleteCheckListCommand;

class CheckListController extends Controller
{
    protected $commandBus;

    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $taskId)
    {
        // dd(json_decode($request->get('checklist')));
        return $this->commandBus->execute(new AddCheckListCommand($request->all(), $taskId));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $taskId, $checkListId)
    {
        // dd(json_decode($request->get('checklist')));
        return $this->commandBus->execute(new EditCheckListCommand($request->all(), $taskId, $checkListId));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($taskId, $checkListId)
    {
        return $this->commandBus->execute(new DeleteCheckListCommand($taskId, $checkListId));
    }

    public function completeCheckList($taskId, $checkListId){
        return $this->commandBus->execute(new CompleteCheckListCommand($taskId, $checkListId));
    }
}
