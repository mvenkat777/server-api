<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Contacts\Commands\AllContactListCommand;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Contacts\Commands\ShowContactByIdCommand;
use Platform\Contacts\Commands\GetContactByUserIdCommand;
use Platform\Contacts\Commands\UpdateContactCommand;
use Platform\Contacts\Commands\DeleteContactCommand;
use Platform\Contacts\Validators\Contacts;

class ContactController extends Controller
{
    /**
     * DefaultCommandBus
     * @var 
     */
    protected $commandBus;

    /**
     * Contact Valodator
     * @var 
     */
    protected $contact;
    

    public function __construct(DefaultCommandBus $commandBus , Contacts $contact)
    {
        $this->commandBus = $commandBus;
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $command=new AllContactListCommand();
        $response=$this->commandBus->execute($command);
        return $response;
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
        $formData =$request->all();
        $this->contact->validate($formData);
        $command = new CreateContactCommand($formData);
        $response = $this->commandBus->execute($command);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command=new ShowContactByIdCommand($id);
        $response=$this->commandBus->execute($command);
        return $response;
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
        $formData = $request->all();
        $command=new UpdateContactCommand($formData, $id);
        $response=$this->commandBus->execute($command);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new DeleteContactCommand($id);
        $response = $this->commandBus->execute($command);
        return $response;
    }

    public function userContact($userId, $contactId)
    {
        $command = new GetContactByUserIdCommand($userId, $contactId);
        $response = $this->commandBus->execute($command);
        return $response;
    }
}
