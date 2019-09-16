<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\Notes\Events\NoteWasShared;
use Platform\Notes\Repositories\Contracts\NoteRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

class ShareNoteCommandHandler implements CommandHandler
{
    use EventGenerator;
    /**
     * @var NoteRepository
     */
    private $noteRepository;
    
    /**
     * @var [type]
     */
    private $dispatcher;

    /**
     * @var [type]
     */
    private $userRepository;

    /**
     * @param NoteRepository
     */
    public function __construct(NoteRepository $noteRepository,
                                UserRepository $userRepository,
                                EventDispatcher $dispatcher)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $sharedTo = [];
        foreach ($command->sharedTo as $email) {
            $user = $this->userRepository->getByEmail($email);
            $sharedTo[$user->id] = ['shared_by' => $command->sharedBy];
        }
        $result = $this->noteRepository->shareNote($command, $sharedTo);
        $note = $this->noteRepository->noteById($command->noteId);
        $email = [];
        if($result['attached']){  
            foreach ($result['attached'] as $id) {
                $user = $this->userRepository->userById($id);
                array_push($email, $user->email);
            }     
            $this->raise(new NoteWasShared($email, \Auth::user(), $note));
            $this->dispatcher->dispatch($this->releaseEvents());
            return 'Shared Successfully';
        }
        elseif ($result) {
            return 'Shared Successfully';
        }
        else{
            throw new SeException('NoteId does not exist', 404, 6320103);
        }

        
        
    }
}

