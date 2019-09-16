<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Notes\Repositories\Contracts\NoteCommentRepository;
use Platform\Notes\Repositories\Contracts\NoteRepository;

class DeleteCommentCommandHandler implements CommandHandler
{
    /**
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * @var NoteRepository
     */
    private $noteCommentRepository;

    /**
     * @param NoteRepository
     */
    public function __construct(NoteRepository $noteRepository,
                                NoteCommentRepository $noteCommentRepository )
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommentRepository = $noteCommentRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $owner='';
        $note = $this->noteRepository->noteById($command->noteId);
        if($note->created_by == \Auth::user()->id){
            $owner = TRUE;
        }
        $result = $this->noteCommentRepository->deleteComment($command, $owner);
        if($result){
            $response='Deleted Successfully';
            return $response;
        }
        else{
            throw new SeException('Id does not exist or Not created by you', 404, 6320102);
        }
    }
}

