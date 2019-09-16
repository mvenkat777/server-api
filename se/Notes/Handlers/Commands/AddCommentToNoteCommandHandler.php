<?php

namespace Platform\Notes\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Notes\Events\CommentWasAdded;
use Platform\Notes\Repositories\Contracts\NoteCommentRepository;
use Platform\Notes\Repositories\Contracts\NoteRepository;
use Platform\Users\Repositories\Contracts\UserRepository;



class AddCommentToNoteCommandHandler implements CommandHandler
{
 
    use EventGenerator;
    /**
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * @var NoteRepository
     */
    private $noteCommentRepository;
    
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
                                EventDispatcher $dispatcher,
                                NoteCommentRepository $noteCommentRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommentRepository = $noteCommentRepository;
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  CreateNoteCommand
     * @return mixed
     */
    public function handle($command)
    {   
        $comment = $this->noteCommentRepository->addComment($command);
        $note = $this->noteRepository->noteById($command->noteId);
        $sharedData = $note->share;
        $email = [];
        foreach ($sharedData as $user) {
            if ($user->email != \Auth::user()->email) {
                array_push($email,$user->email);
            }
        }
        if (!is_null($email)) {
            $this->raise(new CommentWasAdded($email, \Auth::user(), $note));
            $this->dispatcher->dispatch($this->releaseEvents());
        }

        return $comment;
    }
}

