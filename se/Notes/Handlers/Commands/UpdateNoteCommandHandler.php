<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Exceptions\SeException;
use Platform\App\Events\EventGenerator;
use Platform\Notes\Repositories\Contracts\NoteRepository;

class UpdateNoteCommandHandler implements CommandHandler
{

    private $NoteRepository;

    /**
     * @param NoteRepository
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        $data = $this->noteRepository->updateNote($command);

        if($data)
        {
            $response='Updated Successfully';
            return $response;
        }
        else
        {
            throw new SeException('NoteId does not exist or Not created by you', 404, 6320102);
        }
    }

}

