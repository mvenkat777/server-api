<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Notes\Commands\AddCommentToNoteCommand;
use Platform\Notes\Commands\AllNoteListCommand;
use Platform\Notes\Commands\AllSharedNoteListCommand;
use Platform\Notes\Commands\CreateNoteCommand;
use Platform\Notes\Commands\DeleteCommentCommand;
use Platform\Notes\Commands\DeleteNoteCommand;
use Platform\Notes\Commands\ShareNoteCommand;
use Platform\Notes\Commands\ShowNoteByIdCommand;
use Platform\Notes\Commands\UpdateCommentCommand;
use Platform\Notes\Commands\UpdateNoteCommand;
use Platform\Notes\Transformers\NoteCommentTransformer;
use Platform\Notes\Transformers\NoteTransformer;
use Platform\Notes\Validators\Notes;

class NoteController extends ApiController
{
    /**
     * For Calling Commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Shipment Validator
     * @var Platform\Notes\Validators\Notes
     */
    protected $note;
    
    /**
     * @param DefaultCommandBus
     * @param Notes
     */
    public function __construct(DefaultCommandBus $commandBus , Notes $note)
    {
        $this->commandBus = $commandBus;
        $this->note = $note;
        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $command = new AllNoteListCommand($request);
        $response = $this->commandBus->execute($command);

        return $this->respondWithPaginatedCollection($response , new NoteTransformer, 'note');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->all();
        $this->note->validate($formData);
        $command = new CreateNoteCommand($formData);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response,new NoteTransformer,'note');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $noteId
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command = new ShowNoteByIdCommand($id);
        $response = $this->commandBus->execute($command);

        return $this->respondWithItem($response, new NoteTransformer, 'note');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $noteId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formData = $request->all();
        $command = new UpdateNoteCommand($formData, $id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $noteId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new DeleteNoteCommand($id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string noteId
     * @return \Illuminate\Http\Response
     */
    public function shareNote(Request $request, $id)
    {
        $data = $request->all();
        $command = new ShareNoteCommand($data, $id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllSharedNote(Request $request)
    {
        $command = new AllSharedNoteListCommand($request);
        $response = $this->commandBus->execute($command);

        return $this->respondWithPaginatedCollection($response , new NoteTransformer, 'note');
    }

    /**
     * @param Request $request 
     * @param NoteId $id 
     * @param \Illuminate\Http\Response
     */
    public function addComment(Request $request, $id)
    {
        $command = new AddCommentToNoteCommand($request, $id);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response , new NoteCommentTransformer, 'comment');
    }

    /**
     * @param  Request $request 
     * @param  NoteId  $id      
     * @return \Illuminate\Http\Response        
     */
    public function updateComment(Request $request,$noteId, $id)
    {
        $command = new UpdateCommentCommand($request,$noteId, $id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    /**
     * @param  NoteId $id 
     * @param  CommentId $commentId
     * @return \Illuminate\Http\Response
     */
    public function deleteComment($noteId, $commentId)
    {
        $command = new DeleteCommentCommand($noteId, $commentId);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }
}
