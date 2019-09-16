<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Notes\Repositories\Contracts\NoteRepository;
use Platform\App\Exceptions\SeException;

class ShowNoteByIdCommandHandler implements CommandHandler
{
    
    private $noteRepository;

    /**
     * @param AddressRepository
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
        $data = $this->noteRepository->showNote($command);
        if($data != NULL)
        {
            return $data;
        }
        else
        {
            throw new SeException('Note is not created by you and is not share with you', 404, 6320101);
        }
    }
}

