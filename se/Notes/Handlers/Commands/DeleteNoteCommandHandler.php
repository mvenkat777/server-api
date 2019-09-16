<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\CommandHandler;
use Platform\Notes\Repositories\Contracts\NoteRepository;

class DeleteNoteCommandHandler implements CommandHandler
{
    /**
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * @param NoteRepository
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $result = $this->noteRepository->deleteNote($command);
        if($result){
            $response='Deleted Successfully';
            return $response;
        }
        else{
            throw new SeException('Id does not exist or Not created by you', 404, 6320102);
        }
    }
}

