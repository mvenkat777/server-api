<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Notes\Repositories\Contracts\NoteRepository;

class AllNoteListCommandHandler implements CommandHandler
{
    /**
     * @var Platform\Notes\Repositories\Contracts\NoteRepository
     */
    private $noteRepository;

    /**
     * @param Platform\Notes\Repositories\Contracts\NoteRepository
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
       
    }

    /**
     * @param  AllNoteListCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->noteRepository->getAllNotes($command);
    }

}

