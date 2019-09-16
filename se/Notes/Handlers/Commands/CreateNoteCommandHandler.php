<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Notes\Repositories\Contracts\NoteRepository;



class CreateNoteCommandHandler implements CommandHandler
{
 
    /**
     * @var Platform\Notes\Repositories\Contracts\NoteRepository
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
     * @param  CreateNoteCommand
     * @return mixed
     */
    public function handle($command)
    {   
        return $this->noteRepository->makeNote($command);
    }
}

